<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset('images/root/thumbnail_blessed_day.png') }}">
  <title>{{ $couple->groom_name ?? 'Groom' }} & {{ $couple->bride_name ?? 'Bride' }} Intimate Wedding | {{ $brand }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{url('simplycountdown/dist/themes/dark.css')}}" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Antic+Didone&family=Funnel+Display:wght@300..800&family=Italiana&family=Lavishly+Yours&family=Meow+Script&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/blackbrowntheme.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css">
  <link rel="stylesheet" href="{{ asset('css/fluid-gallery.css') }}">
  <style>
    #gallery .item {
    width: 200px;
    float: left;
    }
    #gallery .item img {
        display: block;
        width: 100%;
    }

  </style>

</head>

<body>
  <!-- COVER -->
  <section id="cover" style="--background-image: url('{{ asset($backgroundImages) ?? asset('inv/img/tushar-ranjan-GqpGd6NtUoI-unsplash.jpg') }}');">
    <div class="text-center content">
      <h2 class="fw-light">Wedding Invitation</h2>
      <h1 class="fw-bold display-3">{{ $couple->groom_name ?? 'Groom' }} & {{ $couple->bride_name ?? 'Bride' }}</h1>
      <h3 class="fw-light ">Dear Our Honored Guest <br> {{ $guestName ?? 'Guest' }}</h2>
      <button id="openBtn" class="px-4 py-2 mt-4 openbtn btn btn-light rounded-pill fw-bold">
        Open Invitation
      </button>
    </div>
  </section>

  <!-- HEAD -->
  <section id="head" class="text-center bg-light fade-section" style="--background-image: url('{{ asset($backgroundImages) ?? asset('inv/img/gpt1.png') }}');">
    {{-- <div class="fade-content"> --}}
        <div class="head-top fade-content">
            <h2 class="fw-light">Wedding Invitation</h2>
            <h1 class="fw-bold display-3">{{ $couple->groom_name ?? 'Groom' }} & {{ $couple->bride_name ?? 'Bride' }}'s</h1>
            <p class="fw-light">{{ \Carbon\Carbon::parse($couple->wedding_date)->format('l, d F Y') }}</p>
        </div>
        <div class="head-bottom fade-content" id="playMusic">
            {{-- <p class="fs-4 ">With the blessing of God, we joyfully invite you to our wedding celebration</p> --}}
            <p class="fs-4 ">{{!! $couple->wedding_quote ?? 'With the blessing of God, we joyfully invite you to our wedding celebration' !!}}</p>
        </div>
        <div class="mt-4 countdown simply-countdown-dark fade-content"></div>
    {{-- </div> --}}
  </section>

  <audio id="bg-music">
    <source src="{{  asset('audio/default.mp3') }}" type="audio/mpeg">
  </audio>
  <!-- Audio -->
  <!-- Tombol Musik -->
  <button id="music-toggle" class="btn btn-music">
      <!-- Ikon ON -->
      <i id="icon-sound-on" class="bi bi-volume-up-fill"></i>
      <!-- Ikon OFF -->
      <i id="icon-sound-off" class="bi bi-volume-mute-fill d-none"></i>
  </button>

  <!-- INFO -->
  <section id="info" class="fade-section" style="--background-image: url('{{ asset($backgroundImages) ?? asset('inv/img/tushar-ranjan-GqpGd6NtUoI-unsplash.jpg') }}');">
    <div class="container profile-card">
      <div class="mt-5 row couple ">

        {{-- GROOM SECTION --}}
        @if($groom)
        <div class="col-lg-6 fade-content" style="margin-top:12px !important; margin-bottom:12px !important;">
          <div class="row">

            <!-- Mobile (foto full + overlay teks) -->
            <div class="col-12 d-block d-md-none position-relative portrait-4-5">
              <img src="{{ $groom->image_url ? asset($groom->image_url) : asset('inv/img/gpt2.png') }}"
                  alt="{{ $groom->full_name ?? $couple->groom_name ?? 'Groom' }}"
                  class="rounded img-fluid w-100" style="object-position:70% center;">
              <div class="text-center overlay-info">
                <h3>{{ $groom->full_name ?? $couple->groom_name ?? 'Groom' }}</h3>
              <hr>
                <p>{{ $groom->additional_info ? $groom->additional_info : ' ' }}</p>
                @if($groom->personParent)
                <p>Son of Mr.{{ $groom->personParent->father_name ?? 'Father' }} <br>& Mrs.{{ $groom->personParent->mother_name ?? 'Mother' }}</p>
                @endif
              </div>
            </div>

            <!-- Tablet/Desktop -->
            <div class="col-md-6 d-none d-md-block text-end">
              <h3>{{ $groom->full_name ?? $couple->groom_name ?? 'Groom' }}</h3>
              <p>{{ $groom->additional_info ? $groom->additional_info : ' ' }}</p>
              @if($groom->personParent)
              <p>Son of Mr.{{ $groom->personParent->father_name ?? 'Father' }} & Mrs.{{ $groom->personParent->mother_name ?? 'Mother' }}</p>
              @endif
            </div>

            <div class="col-md-6 d-none d-md-block portrait-4-5">
              <img src="{{ $groom->image_url ? asset($groom->image_url) : asset('inv/img/gpt2.png') }}"
                  alt="{{ $groom->full_name ?? $couple->groom_name ?? 'Groom' }}"
                  class="rounded img-fluid w-100" style="object-position:70% center;">
            </div>

          </div>
        </div>
        @endif

        {{-- BRIDE SECTION --}}
        @if($bride)
        <div class="col-lg-6 fade-content" style="margin-top:12px !important; margin-bottom:12px !important;">
          <div class="row">

            <!-- Mobile (foto full + overlay teks) -->
            <div class="col-12 d-block d-md-none position-relative portrait-4-5">
              <img src="{{ $bride->image_url ? url($bride->image_url) : url('inv/img/gpt3.png') }}"
                  alt="{{ $bride->full_name ?? $couple->bride_name ?? 'Bride' }}"
                  class="rounded img-fluid w-100" style="object-position:20% center;">
              <div class="text-center overlay-info">
                <h3>{{ $bride->full_name ?? $couple->bride_name ?? 'Bride' }}</h3>
                <hr>
                <p>{{ $bride->additional_info ? $bride->additional_info : ' ' }}</p>
                @if($bride->personParent)
                <p>Daughter of Mr.{{ $bride->personParent->father_name ?? 'Father' }} <br>& Mrs.{{ $bride->personParent->mother_name ?? 'Mother' }}</p>
                @endif
              </div>
            </div>

            <!-- Tablet/Desktop -->
            <div class="col-md-6 d-none d-md-block portrait-4-5">
              <img src="{{ $bride->image_url ? url($bride->image_url) : url('inv/img/gpt3.png') }}"
                  alt="{{ $bride->full_name ?? $couple->bride_name ?? 'Bride' }}"
                  class="rounded img-fluid w-100" style="object-position:20% center;">
            </div>

            <div class="col-md-6 d-none d-md-block text-start">
              <h3>{{ $bride->full_name ?? $couple->bride_name ?? 'Bride' }}</h3>
              <p>{{ $bride->additional_info ? $bride->additional_info : '' }}</p>
              @if($bride->personParent)
              <p>Daughter of Mr.{{ $bride->personParent->father_name ?? 'Father' }} & Mrs.{{ $bride->personParent->mother_name ?? 'Mother' }}</p>
              @endif
            </div>

          </div>
        </div>
        @endif

      </div>
    </div>
  </section>
  <!--gallery v1-->
@if($galleryImages && $galleryImages->count() > 0)
  <section id="gallery"  class=" fade-section">
      <div class="mb-5 text-center fade-content">
       <h2 class="fw-bold">Captured Intimacy</h2>
       <p class="">Setiap bingkai menyimpan kisah penuh kehangatan dan kedekatan</p>
      </div>
    <div class="tz-gallery">

        <div class="row ">
@foreach($galleryImages as $image)
            <div class="col-sm-12 col-md-4 fade-content">
                {{-- <a class="lightbox" href="../images/bridge.jpg"> --}}
                  <a href="{{ url($image->image_url) }}" data-toggle="lightbox" data-gallery="example-gallery" data-size="lg"  class="col-sm-4 gallery-item">
                    <img src="{{ url($image->image_url) }}" alt="Bridge" class="img-fluid " style="border-radius:15px">
                </a>
            </div>
@endforeach

        </div>

    </div>
  </section>
  @endif 
  <!-- LOCATION -->
  @if($location)
  <section id="location" class="fade-section" style="--background-image: url('{{ asset($backgroundImages) ?? asset('inv/img/gpt.png') }}');">
    <div class="container text-center ">
      <div class="container profile-card ">
        <h2>Holly Matrimony</h2>
        <div class="mt-5 row couple ">
                <div class="col-lg-6 fade-content" style="margin-top: 12px !important; margin-bottom: 12px !important;">
                  <h3>{{ \Carbon\Carbon::parse($weddingEvent->event_date)->format('l, d F Y') }}</h3>
                  <h3>{{ $weddingEvent->event_time }} - End</h3>
                  <h3>{{ $location->venue_name }}</h3>
                  <h3>{{ $location->address }}</h3>
                </div>
                <div class="col-lg-6 fade-content map-container" style="margin-top: 12px !important; margin-bottom: 12px !important;">
                  @if($location->map_embed_url)
                  {!! $location->map_embed_url !!}
                  @else
                  <p>Location details will be updated soon</p>
                  @endif
                </div>
            </div>
      </div>
    </div>
  </section>
  @endif
  <!-- Invitation -->
  <section id="invitation" class="fade-section">
    <div class="fade-content">
      <h2 class="mb-4">Your Invitation</h2>
    </div>
    <div class="invitation-card fade-content" >
    <!-- Bagian kiri: Barcode -->
      <div class="card-left">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $invitation->invitation_code }}" 
            alt="Invitation Barcode" />
        <p class="code">Kode: {{ $invitation->invitation_code }}</p>
      </div>

    <!-- Bagian kanan: Informasi Undangan -->
      <div class="card-right fade-content">
        <h2 class="invite-header">Undangan Spesial</h2>
        <h2 class="invite-detail"><strong>Kepada</strong><br> {{ $guestName }}</h2>
        <h2 class="invite-header">Holly Matrimony</h2>
        <p class="invite-detail"><i class="bi bi-calendar-check fs-4"></i> {{ \Carbon\Carbon::parse($weddingEvent->event_date)->format('l, d F Y') }}</p>
        <p class="invite-detail"><i class="bi bi-clock fs-4"></i> {{ $weddingEvent->event_time }} WIB</p>
        <p class="invite-detail"><i class="bi bi-geo-alt fs-4"></i> {{ $location->venue_name ?? 'Venue' }}, {{ $location->address ?? 'Address' }}</p>
      </div>
    </div>
  </section>

  <!-- Story -->
  @if($timelineEvents && $timelineEvents->count() > 0)
  <section id="story" class="py-5 story fade-section" style="--background-image: url('{{ asset($backgroundImages) ?? asset('inv/img/gpt.png') }}');">
    <div class="container fade-content">
      <h2 class="mb-5 text-center" style="">Our Journey</h2>
    </div>
    <div class="container profile-card">
      @foreach($timelineEvents as $index => $event)
      @if($index % 2 == 0)
        <ul class="timeline fade-content">
          <li>
            <div class="timeline-image" style="background-image: url('{{ $event->image_url ? asset($event->image_url) : asset('inv/img/gpt2.png') }}') ;"></div>
            <div class="timeline-panel">
              <div class="timeline-heading">
                    <h5>{{ $event->title }}</h5>
              </div>
              <div class="timeline-body">
                    <p class="mt-0 ">{{ \Carbon\Carbon::parse($event->event_date)->format('F Y') }}</p>
                <p>{{ $event->description }}</p>
              </div>
          </li>
        </ul>
      @else
        <ul class="timeline fade-content">
          <li class="timeline-inverted">
              <div class="timeline-image" style="background-image: url('{{ $event->image_url ? asset($event->image_url) : asset('inv/img/gpt2.png') }}');"></div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                    <h5>{{ $event->title }}</h5>
                  </div>
                  <div class="timeline-body">
                    <p class="mt-0 ">{{ \Carbon\Carbon::parse($event->event_date)->format('F Y') }}</p>
                    <p>{{ $event->description }}</p>
                  </div>
              </div>
          </li>
        </ul>
        @endif
        @endforeach
    </div>
  </section>
  @endif
  <!-- RSVP -->
  <section id="rsvp" class="fade-section">
    <div class="container">
      <div class="fade-content">
        <h2 class="mb-4" style="">RSVP</h2>
        <p class="mb-5 text-center">Confirm your attendance to our special day</p>
      </div>
      
      <div class="row justify-content-center fade-content">
        <div class="col-lg-8">
          <div class="p-4 shadow card" style="border-radius:20px; background: #fff;">
            <div class="card-body">
              <!-- RSVP Form -->
              <form id="rsvpForm">
                <input type="hidden" name="invitation_id" value="{{ $invitation->id }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="mb-4 text-center">
                  <h4 class="mb-3">Will you be attending our wedding?</h4>
                  <div class="gap-3 d-flex justify-content-center">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="is_attending" id="attendingYes" value="1">
                      <label class="form-check-label fs-5" for="attendingYes">Yes, I will be there!</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="is_attending" id="attendingNo" value="0">
                      <label class="form-check-label fs-5" for="attendingNo">Sorry, I can't make it</label>
                    </div>
                  </div>
                </div>
                
                <div class="mb-3">
                  <label for="guest_count" class="form-label">Number of guests attending (including yourself)</label>
                  <input type="number" class="form-control" id="guest_count" name="guest_count" min="1" max="10" value="1" required>
                </div>
                
                {{-- <div class="mb-3">
                  <label for="message" class="form-label">Message (optional)</label>
                  <textarea class="form-control" id="message" name="message" rows="3" placeholder="Leave us a message..."></textarea>
                </div> --}}
                
                <div class="d-grid">
                  <button type="submit" class="btn btn-pink" id="rsvpSubmitBtn">Confirm Attendance</button>
                </div>
              </form>
              
              <!-- RSVP Status Display -->
              <div id="rsvpStatus" class="mt-4" style="display: none;">
                <div class="text-center alert alert-success">
                  <h5 class="mb-2">Thank you for your response!</h5>
                  <p class="mb-0">Your attendance has been recorded.</p>
                </div>
              </div>
            </div>
          </div>
          
          {{-- <!-- Attendance Statistics -->
          <div class="mt-4 text-center">
            <h4>Attendance Status</h4>
            <div class="row">
              <div class="col-md-4">
                <div class="p-3 card">
                  <h5 class="text-success">{{ $invitation->is_attending !== null ? ($invitation->is_attending ? 'CONFIRMED' : 'REGRETFULLY ABSENT') : 'PENDING' }}</h5>
                  <p>Your Status</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 card">
                  <h5 id="attendingCount">0</h5>
                  <p>Guests Attending</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 card">
                  <h5 id="totalCount">0</h5>
                  <p>Total Invited</p>
                </div>
              </div>
            </div>
          </div> --}}
        </div>
      </div>
    </div>
  </section>
  <!-- Gift -->
  <section id="gift" class="fade-section">
    <div class="container">
      <h2 class="mb-4 text-center" style="">Wedding Gift</h2>

      <!-- Card Gift -->
      <!-- Card Informasi -->
      @if($gifts && $gifts->count() > 0)
      <p class="mb-5 text-center">Doa restu Anda sudah merupakan hadiah terbaik bagi kami. Namun jika ingin memberikan tanda kasih, dapat melalui rekening atau alamat berikut:</p>
      <div class="mb-5 text-center border-0 shadow-lg card" style="border-radius:20px;">
        <div class="card-body">
          @foreach($gifts as $gift)
          @if($gift->gift_type === 'support')
          {{-- <h5 class="mt-2 mb-3 fw-bold">Transfer Hadiah</h5> --}}
          <p><b>Rekening :</b></p>
          <p><strong>{{$gift->bank_name}}</strong><br>{{$gift->account_number}}<br>a.n. {{$gift->account_holder_name}}</p>
          <button class="mb-3 btn btn-outline-pink copy-btn" data-account="{{$gift->account_number}}">
            Salin Nomor Rekening
          </button>
          @elseif($gift->gift_description)
          <p><b>Alamat : </b></p>
          <p>{{$gift->gift_description}}</p>
           <button class="mb-3 btn btn-outline-pink copy-btn" data-account="{{$gift->gift_description}}">
            Salin Alamat
          </button>
          @endif
          @endforeach
        </div>
      </div>
    @else
      <div class="mb-5 text-center border-0 shadow-lg card" style="border-radius:20px;">
        <div class="p-4 card-body">
          <h5 class="mb-3 fw-bold">Tanpa Mengurangi Rasa Hormat</h5>
          <p class="mb-0" style="font-size:1.1rem; line-height:1.7;">
            Kami dengan tulus memohon agar tidak memberikan sumbangan dalam bentuk apapun.  
            Kehadiran Anda dalam hari bahagia kami sudah lebih dari cukup,  
            dan kami hanya ingin berbagi kebahagiaan bersama orang-orang yang kami cintai. 💕
          </p>
        </div>
      </div>
    @endif

      <!-- Card Form -->
      <div class="mb-4 border-0 shadow card" style="border-radius:20px;">
        <div class="card-body">
          <h5 class="mb-3 text-center fw-bold " >Kirim Pesan & Doa</h5>
          <form id="giftForm">
            <div class="mb-3">
              <input type="text" class="form-control" name="nama" id="guestNameInput" placeholder="Nama Anda" value="{{ $guestName ?? '' }}" required readonly>
              {{-- <input type="text" class="form-control" name='idGuest' value={{$guestId}} hidden> --}}
            </div>
            <div class="mb-3">
              <textarea class="form-control" name="pesan" rows="3" placeholder="Tulis pesan & doa..." required></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-pink">Kirim</button>
            </div>
          </form>
        </div>
      </div>

      <!-- List Pesan -->
      {{-- @dd($guestMessages) --}}
      <div class="mt-4 messages fade-content">
        <h5 class="mb-4 text-center text-white">Pesan & Doa</h5>
        <div id="messageList" class="gap-3 messageList d-flex flex-column message-box">
          @if($guestMessages && $guestMessages->count() > 0)
            @foreach($guestMessages->where('is_approved', true)->sortByDesc('created_at') as $guestMessage)
              <div class="message-card fade-in">
                <h4>{{ $guestMessage->guest_name }}:</h4>
                <p>{{ $guestMessage->message }}</p>
              </div>
            @endforeach
          @else
            <div class="text-center text-muted">
              <p>Belum ada pesan. Jadilah yang pertama memberikan pesan & doa!</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="p-3 text-center text-white bg-dark">
    <img src="{{ asset('images/root/blessed_day.png') }}" alt="Footer Image" style="height: 80px; margin-bottom:10px;">
    <p class="mb-0">© 2025 {{ $couple->groom_name ?? 'Groom' }} & {{ $couple->bride_name ?? 'Bride' }} Wedding Invitation | Powered by {{ $brand }}</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>//cover open
    const cover = document.getElementById('cover');
    const openBtn = document.getElementById('openBtn');

    openBtn.addEventListener('click', () => {
      cover.classList.add('hidden');
      document.body.style.overflow = 'auto'; // unlock scroll

      // Trigger fade-in pertama untuk head setelah cover hilang
      setTimeout(() => {
        document.querySelectorAll('#head .fade-content').forEach(el => {
          el.classList.add('visible');
        });
      }, 600);
    });

    // Observer untuk semua section setelah head
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.querySelectorAll('.fade-content').forEach(el => {
            el.classList.add('visible');
          });
        }
      });
    }, { threshold: 0.2 });

    // observe semua section (kecuali cover)
    document.querySelectorAll('.fade-section').forEach(section => {
      if (section.id !== 'head') observer.observe(section);
    });
    
    // Special handling for gallery section to ensure it works with many images
    const gallerySection = document.getElementById('gallery');
    if (gallerySection) {
      // Try to manually trigger fade-in if gallery section exists
      const galleryObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            // Add visible class to all fade-content elements in the gallery
            setTimeout(() => { // Small delay to ensure proper rendering
              entry.target.querySelectorAll('.fade-content').forEach(el => {
                if (!el.classList.contains('visible')) {
                  el.classList.add('visible');
                }
              });
            }, 100);
          }
        });
      }, { threshold: 0.1 }); // Lower threshold for gallery to trigger more easily
      
      galleryObserver.observe(gallerySection);
      
      // Fallback: make sure gallery elements appear after some time if intersection observer fails
      setTimeout(() => {
        if (gallerySection.querySelectorAll('.fade-content:not(.visible)').length > 0) {
          gallerySection.querySelectorAll('.fade-content').forEach(el => {
            if (!el.classList.contains('visible')) {
              el.classList.add('visible');
            }
          });
        }
      }, 2000); // 2 seconds as fallback
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.5/dist/index.bundle.min.js"></script>
  <script src="{{url('simplycountdown/dist/simplyCountdown.umd.js')}}"></script>
  <script>//countdown
        simplyCountdown(".countdown", {
            year: 2025,
            month: 12,
            day: 25,
            hours: 9,
            words: {
                days: {
                    lambda: (root, count) => (count === 1 ? "Hari" : "Hari"),
                    root: "Hari",
                },
                hours: {
                    lambda: (root, count) => (count === 1 ? "Jam" : "Jam"),
                    root: "Jam",
                },
                minutes: {
                    lambda: (root, count) => (count === 1 ? "Menit" : "Menit"),
                    root: "Menit",
                },
                seconds: {
                    lambda: (root, count) => (count === 1 ? "Detik" : "Detik"),
                    root: "Detik",
                },
            },
        });
  </script>
  <script>// Copy rekening
    document.querySelectorAll(".copy-btn").forEach(btn => {
        btn.addEventListener("click", function () {
          navigator.clipboard.writeText(this.dataset.account);
          this.innerText = "Disalin!";
          setTimeout(() => this.innerText = "Salin Nomor Rekening", 2000);
        });
      });

     // Simpan pesan ke backend
      const form = document.getElementById("giftForm");
      const messageList = document.getElementById("messageList");

      form.addEventListener("submit", function(e){
        e.preventDefault();
        
        const formData = {
          guest_name: this.nama.value.trim(),
          message: this.pesan.value.trim(),
          invitation_id: {{ $invitation->id ?? 0 }}, // Assuming invitation ID is available in the view
          _token: '{{ csrf_token() }}'
        };

        if(formData.guest_name && formData.message){
          // Show loading state
          const submitBtn = this.querySelector('button[type="submit"]');
          const originalText = submitBtn.textContent;
          submitBtn.textContent = 'Mengirim...';
          submitBtn.disabled = true;

          fetch('/api/guest-messages', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': formData._token
            },
            body: JSON.stringify(formData)
          })
          .then(response => response.json())
          .then(data => {
            console.log("llll")
            if(data.success) {
              // Add the new message to the top of the list
              const newMessageHtml = `
               <div class="message-card fade-in">
                <h4>${formData.guest_name}:</h4>
                <p>${formData.message}</p>
              </div>
              `;
              
              // If the "no messages" text is displayed, remove it
              const noMessageElement = messageList.querySelector('.text-center');
              if (noMessageElement) {
                messageList.innerHTML = newMessageHtml;
              } else {
                messageList.insertAdjacentHTML('afterbegin', newMessageHtml);
              }
              
              // Reset form
              this.reset();
              alert('Pesan berhasil dikirim!');
            } else {
              alert('Gagal mengirim pesan: ' + (data.message || 'Terjadi kesalahan'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim pesan');
          })
          .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
          });
        }
      });
  </script>
  <script>
    const btn = document.getElementById("music-toggle");
    const music = document.getElementById("bg-music");
    const iconOn = document.getElementById("icon-sound-on");
    const iconOff = document.getElementById("icon-sound-off");
    const homeSection = document.getElementById("playMusic");

    let musicAllowed = true;  // baru true setelah user klik open
    let musicStarted = false;  // biar cuma sekali jalan

    
    // Observer untuk home section
    const observer1 = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && musicAllowed && !musicStarted) {
                music.play().then(() => {
                    console.log("🎵 Musik mulai di #home");
                    btn.classList.remove("d-none");
                    iconOn.classList.remove("d-none");
                    iconOff.classList.add("d-none");
                    musicStarted = true;
                }).catch(err => console.error("🚫 Musik gagal play:", err));
            }
        });
    }, { threshold: 0.2 });

    observer1.observe(homeSection);

    // Toggle musik manual
    btn.addEventListener("click", () => {
        if (music.paused) {
            music.play();
            iconOn.classList.remove("d-none");
            iconOff.classList.add("d-none");
        } else {
            music.pause();
            iconOn.classList.add("d-none");
            iconOff.classList.remove("d-none");
        }
    });
  </script>

  <script>
    // RSVP Form Handler
    document.addEventListener('DOMContentLoaded', function() {
      // Load attendance stats when page loads
      loadAttendanceStats();
      
      const rsvpForm = document.getElementById('rsvpForm');
      if (rsvpForm) {
        rsvpForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = {
            invitation_id: this.invitation_id.value,
            is_attending: this.is_attending.value==1?true:false,
            guest_count: this.guest_count.value,
            _token: this._token.value
          };
          
          // Show loading state
          const submitBtn = document.getElementById('rsvpSubmitBtn');
          const originalText = submitBtn.textContent;
          submitBtn.textContent = 'Processing...';
          submitBtn.disabled = true;
          
          fetch('/api/invitations/rsvp', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': formData._token
            },
            body: JSON.stringify(formData)
          })
          .then(response => response.json())
          .then(data => {
            if(data.success) {
              // Show success message
              document.getElementById('rsvpStatus').style.display = 'block';
              
              // Update attendance stats
              loadAttendanceStats();
              
              // Disable form after successful submission
              document.querySelectorAll('#rsvpForm input, #rsvpForm textarea, #rsvpForm button').forEach(el => {
                el.disabled = true;
              });
              
              alert('Thank you for confirming your attendance!');
            } else {
              alert('Error submitting RSVP: ' + (data.message || 'Unknown error'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting your RSVP');
          })
          .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
          });
        });
      }
      
      // If invitation already has a response, disable the form
      @if($invitation->is_attending !== null)
        document.querySelectorAll('#rsvpForm input, #rsvpForm textarea, #rsvpForm button').forEach(el => {
          el.disabled = true;
        });
        document.getElementById('rsvpStatus').style.display = 'block';
      @endif
    });
    
    // Function to load attendance stats
    function loadAttendanceStats() {
      // This would typically be an AJAX call to get the stats
      // For now, we'll use a static count based on invitation data
      fetch(`/api/invitations/${@json($invitation->id)}/stats`)
        .then(response => response.json())
        .then(data => {
          if(data.success) {
           console.log("Success");
          }
        })
        .catch(error => {
          console.error('Error loading stats:', error);
          // Fallback: show information based on current invitation
        });
    }
  </script>

</body>

</html>
