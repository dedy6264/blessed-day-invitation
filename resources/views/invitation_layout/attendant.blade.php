<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/root/thumbnail_blessed_day.png') }}">
  <title>{{ $weddingEvent->event_name }} Intimate Wedding | {{ $brand }}</title>
  <title>Guest Attendance Scanner</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
      min-height: 100vh;
      padding: 20px;
      color: #333;
    }

    .header-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 20px;
      margin: 0 auto 20px;
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      color: white;
      max-width: 1200px;
    }

    .back-button {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      color: white;
      font-size: 18px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .back-button:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.05);
    }

    .header-content {
      flex: 1;
      text-align: center;
    }

    .header-content h1 {
      font-size: 1.8rem;
      margin-bottom: 5px;
      font-weight: 600;
    }

    .header-content h2 {
      font-size: 1.2rem;
      opacity: 0.9;
      font-weight: 400;
    }

    /* ==== MAIN LAYOUT ==== */
    .main-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .scanner-attendance-wrapper {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .scanner-box, .attendance-section {
      flex: 1;
      min-width: 320px;
      background: white;
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .scanner-title, .attendance-title {
      font-size: 1.5rem;
      margin-bottom: 20px;
      color: #2c3e50;
      font-weight: 600;
      text-align: center;
    }

    video {
      width: 100%;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      background: #000;
      border: 3px solid #e0e0e0;
    }

    .manual-input {
      width: 100%;
      margin-top: 15px;
      padding: 15px;
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      font-size: 16px;
      transition: border-color 0.3s;
    }

    .manual-input:focus {
      outline: none;
      border-color: #2575fc;
      box-shadow: 0 0 0 3px rgba(37, 117, 252, 0.2);
    }

    .response-box {
      background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
      border-radius: 15px;
      padding: 20px;
      margin-top: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      border: 1px solid #e0e0e0;
    }

    .response-title {
      font-size: 1.2rem;
      margin: 0 0 15px;
      color: #2c3e50;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    #responseData {
      font-size: 1rem;
      line-height: 1.5;
      color: #444;
      min-height: 60px;
    }

    .attendance-list {
      max-height: 500px;
      overflow-y: auto;
      padding-right: 10px;
    }

    .attendance-list::-webkit-scrollbar {
      width: 8px;
    }
    .attendance-list::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }

    .attendance-list ul {
      list-style: none;
      padding: 0;
    }

    .attendance-list li {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
      padding: 15px;
      margin-bottom: 12px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      border-left: 4px solid #2575fc;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: transform 0.2s;
    }

    .attendance-list li:hover {
      transform: translateX(5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .guest-name {
      font-weight: 600;
      color: #2c3e50;
      font-size: 1.1rem;
    }

    .guest-status {
      font-size: 0.9rem;
      color: #666;
    }

    .guest-time {
      background: #e8f0ff;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 500;
      color: #2575fc;
    }

    @media (max-width: 768px) {
      .scanner-attendance-wrapper {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="main-container">
    <!-- Header -->
    <div class="header-container">
      <button class="back-button" onclick="goToDashboard()">
        <i class="fas fa-home"></i>
      </button>
      <div class="header-content">
        <h1>Guest Attendance Scanner</h1>
        <h2>{{ $weddingEvent->event_name }}</h2>
      </div>
      <div style="width: 50px;"></div>
    </div>

    <!-- Main content: Scanner (kiri) + Attendance list (kanan) -->
    <div class="scanner-attendance-wrapper">
      <!-- Scanner -->
      <div class="scanner-box">
        <h2 class="scanner-title">QR Scanner</h2>
        <video id="preview" autoplay playsinline muted></video>
        <input type="text" id="manualInput" class="manual-input" placeholder="Masukkan kode tamu secara manual" />
        <div class="response-box" id="responseBox">
          <h3 class="response-title"><i class="fas fa-user-circle"></i> Data Tamu</h3>
          <p id="responseData">Belum ada data</p>
        </div>
      </div>

      <!-- Attendance -->
      <div class="attendance-section">
        <h3 class="attendance-title"><i class="fas fa-list-check"></i> Daftar Presensi</h3>
        <div class="attendance-list">
          <ul id="attendanceList">
            @foreach($presentGuests as $presentGuest)
              <li>
                <div>
                  <div class="guest-name">{{ $presentGuest->guest->name ?? 'Unknown Guest' }}</div>
                  <div class="guest-status">Hadir pada: {{ $presentGuest->checked_in_at ? $presentGuest->checked_in_at->format('H:i:s') : 'Unknown time' }}</div>
                </div>
                <div class="guest-time">
                  {{ $presentGuest->checked_in_at ? $presentGuest->checked_in_at->format('H:i') : 'N/A' }}
                </div>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
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
          responseData.innerHTML = `<strong>Nama:</strong> ${data.name}<br><strong>Status:</strong> ${data.status}`;
          const li = document.createElement('li');
          
          const now = new Date();
          const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
          
          li.innerHTML = `
            <div class="guest-info">
              <div class="guest-name">${data.name}</div>
              <div class="guest-status">${data.status} pada: ${timeString}</div>
            </div>
            <div class="guest-time">${timeString}</div>
          `;
          attendanceList.prepend(li);

          // 🚫 stop scanning sementara
          isScanning = false;
          responseData.innerHTML += "<br><span style='color: #ff6b6b;'>⏳ Tunggu 5 detik sebelum scan berikutnya...</span>";
          
          // aktifkan kembali setelah 5 detik
          clearTimeout(cooldownTimer);
          cooldownTimer = setTimeout(() => {
            isScanning = true;
            responseData.innerHTML = "<span style='color: #4CAF50;'>Silakan scan lagi.</span>";
          }, 5000);
        } else {
          responseData.innerHTML = `<span style='color: #ff6b6b;'>Warning: ${data.message}</span>`;
        }
      })
      .catch(() => {
        responseData.innerHTML = "<span style='color: #ff6b6b;'>Error membaca data tamu.</span>";
      });
    }

    manualInput.addEventListener('keyup', (e) => {
      if (e.key === 'Enter') {
        processCode(e.target.value);
        e.target.value = "";
      }
    });

    // Function to go back to dashboard
    function goToDashboard() {
      // Check the user's role to determine the appropriate dashboard
      const isAdmin = window.location.pathname.includes('/admin');
      if (isAdmin) {
        window.location.href = '/admin/dashboard';
      } else {
        // For client users, redirect to client dashboard
        window.location.href = '/client/dashboard';
      }
    }

    document.addEventListener('DOMContentLoaded', startCamera);
  </script>
</body>
</html>
