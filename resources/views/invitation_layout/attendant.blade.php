<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>QR Attendance Scanner</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f9; color: #333; }
    header { background: #2c3e50; color: white; padding: 15px; text-align: center; }
    .scanner-container { padding: 20px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
    video { width: 100%; max-width: 400px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); background: #000; }
    input { padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ccc; border-radius: 8px; }
    .response-box { background: white; border-radius: 8px; padding: 15px; width: 100%; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
    .response-box h3 { margin: 0 0 10px; font-size: 18px; color: #2c3e50; }
    .list-presensi { margin-top: 20px; width: 100%; max-width: 500px; }
    .list-presensi h3 { margin-bottom: 10px; }
    .list-presensi ul { list-style: none; padding: 0; }
    .list-presensi li { background: #fff; padding: 10px; margin-bottom: 8px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
  </style>
</head>
<body>

<header>
  <h1>Guest Attendance Scanner</h1>
  <h2>{{ $weddingEvent->event_name }} </h2>
</header>

<div class="scanner-container">
  <!-- Camera preview -->
  <video id="preview" autoplay playsinline muted></video>

  <!-- Manual input -->
  <input type="text" id="manualInput" placeholder="Masukkan kode tamu secara manual" />

  <!-- Response -->
  <div class="response-box" id="responseBox">
    <h3>Data Tamu</h3>
    <p id="responseData">Belum ada data</p>
  </div>

  <!-- List presensi -->
  <div class="list-presensi">
    <h3>Daftar Presensi</h3>
    <ul id="attendanceList">
      @foreach($presentGuests as $presentGuest)
        <li>{{ $presentGuest->guest->name ?? 'Unknown Guest' }} - hadir ({{ $presentGuest->checked_in_at ? $presentGuest->checked_in_at->format('H:i:s') : 'Unknown time' }})</li>
      @endforeach
    </ul>
  </div>
</div>

<!-- Load jsQR library -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
  const video = document.getElementById('preview');
  const manualInput = document.getElementById('manualInput');
  const responseData = document.getElementById('responseData');
  const attendanceList = document.getElementById('attendanceList');
  const canvasElement = document.createElement('canvas');
  const canvas = canvasElement.getContext('2d');

  let isScanning = true; // flag supaya tidak double scan
  let cooldownTimer = null;

  async function startCamera() {
    try {
      const isLocalhost = ['localhost', '127.0.0.1'].includes(location.hostname);
      if (!navigator.mediaDevices || (!window.isSecureContext && !isLocalhost)) {
        responseData.innerText = "Gunakan HTTPS atau localhost untuk akses kamera.";
        return;
      }
      const constraints = { video: { facingMode: "environment" } };
      const stream = await navigator.mediaDevices.getUserMedia(constraints);
      video.srcObject = stream;
      video.addEventListener('loadedmetadata', () => {
        canvasElement.width = video.videoWidth || 640;
        canvasElement.height = video.videoHeight || 480;
        startQRScanner();
      });
    } catch (err) {
      responseData.innerText = `Gagal akses kamera: ${err.message}`;
    }
  }

  function startQRScanner() {
    if (video.readyState === video.HAVE_ENOUGH_DATA && isScanning) {
      canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
      const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
      const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: "dontInvert" });
      if (code) processCode(code.data);
    }
    requestAnimationFrame(startQRScanner);
  }

  function processCode(code) {
    if (!code || !isScanning) return;

    let payload = {
      couple_id: {{ $weddingEvent->couple_id }},
    };

    if (/^\d+$/.test(code)) {
      payload.code = code;
    } else {
      payload.invitation_code = code;
    }

    if (code.startsWith('http')) {
      try {
        const url = new URL(code);
        const pathParts = url.pathname.split('/');
        const idFromPath = pathParts[pathParts.length - 1];
        if (/^\d+$/.test(idFromPath)) {
          payload = { code: idFromPath };
        } else {
          payload = { invitation_code: idFromPath };
        }
      } catch (e) {
        payload = { invitation_code: code };
      }
    }

    fetch('/my-guests-attendant', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        responseData.innerText = `Nama: ${data.name}\nStatus: ${data.status}`;
        const li = document.createElement('li');
        li.innerText = `${data.name} - ${data.status} (${new Date().toLocaleTimeString()})`;
        attendanceList.prepend(li);

        // 🚫 stop scanning sementara
        isScanning = false;
        responseData.innerText += "\n⏳ Tunggu 5 detik sebelum scan berikutnya...";
        
        // aktifkan kembali setelah 30 detik
        clearTimeout(cooldownTimer);
        cooldownTimer = setTimeout(() => {
          isScanning = true;
          responseData.innerText = "Silakan scan lagi.";
        }, 5000);
      } else {
        responseData.innerText = `Warning: ${data.message}`;
      }
    })
    .catch(() => {
      responseData.innerText = "Error membaca data tamu.";
    });
  }

  manualInput.addEventListener('keyup', (e) => {
    if (e.key === 'Enter') {
      processCode(e.target.value);
      e.target.value = "";
    }
  });

  document.addEventListener('DOMContentLoaded', startCamera);
</script>

</body>
</html>
