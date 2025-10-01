<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Invitation Platform - Beautiful Themes & Packages</title>
    <link rel="icon" type="image/png" href="{{ asset('images/root/thumbnail_blessed_day.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <img src="{{ asset('images/root/blessed_day.png') }}" alt="Logo" height="40" class="me-2">
                <span class="d-flex align-items-center">
                    <span>Blessed Day</span>
                    <span class="badge bg-warning text-dark ms-2">Digital</span>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#digital-features"><i class="bi bi-star me-1"></i> Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#themes">Themes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#packages">Packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="#register">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="text-center hero-section">
        <div class="container hero-content">
            <h1 class="mb-4 display-2 fw-bold fade-in-up">
                <span class="text-warning">Digital</span> Wedding Invitations
            </h1>
            <p class="mb-5 hero-subtitle lead fade-in-up" style="animation-delay: 0.1s;">
                Create stunning digital invitations that capture your love story and connect with guests instantly
            </p>
            
            <div class="hero-features fade-in-up" style="animation-delay: 0.2s;">
                <div class="hero-feature">
                    <i class="bi bi-send-check"></i>
                    <span>Instant Delivery</span>
                </div>
                <div class="hero-feature">
                    <i class="bi bi-qr-code"></i>
                    <span>QR Code RSVP</span>
                </div>
                <div class="hero-feature">
                    <i class="bi bi-image"></i>
                    <span>Beautiful Designs</span>
                </div>
                <div class="hero-feature">
                    <i class="bi bi-globe"></i>
                    <span>Global Access</span>
                </div>
            </div>
            
            <div class="mt-5 fade-in-up" style="animation-delay: 0.3s;">
                <a href="#register" class="px-5 btn btn-light btn-lg rounded-3 me-3">Create Your Invitation</a>
                <a href="#themes" class="px-5 btn btn-outline-light btn-lg rounded-3">Explore Themes</a>
            </div>
        </div>
    </section>

    <!-- Digital Features Section -->
    <section id="digital-features" class="py-5">
        <div class="container">
            <h2 class="section-title fade-in-up">Why Choose Digital Invitations?</h2>
            <p class="mb-5 text-center text-muted fade-in-up" style="animation-delay: 0.1s;">Modern, eco-friendly, and feature-rich solutions for your special day</p>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="p-4 text-center border-0 shadow-sm card h-100 fade-in-up" style="animation-delay: 0.2s;">
                        <div class="card-body">
                            <div class="mx-auto mb-4 bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-send-check text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title">Instant Delivery</h5>
                            <p class="card-text">Send invitations to guests worldwide with just a click. No postal delays or physical costs.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="p-4 text-center border-0 shadow-sm card h-100 fade-in-up" style="animation-delay: 0.3s;">
                        <div class="card-body">
                            <div class="mx-auto mb-4 bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-qr-code text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title">Smart RSVP</h5>
                            <p class="card-text">Integrated RSVP system with QR codes for easy attendance tracking and responses.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="p-4 text-center border-0 shadow-sm card h-100 fade-in-up" style="animation-delay: 0.4s;">
                        <div class="card-body">
                            <div class="mx-auto mb-4 bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-globe text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title">Global Access</h5>
                            <p class="card-text">Accessible on any device, anywhere in the world. Perfect for destination weddings.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="p-4 text-center border-0 shadow-sm card h-100 fade-in-up" style="animation-delay: 0.5s;">
                        <div class="card-body">
                            <div class="mx-auto mb-4 bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title">Guest Management</h5>
                            <p class="card-text">Track RSVPs, manage guest lists, and send updates to all guests easily.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="p-4 text-center border-0 shadow-sm card h-100 fade-in-up" style="animation-delay: 0.6s;">
                        <div class="card-body">
                            <div class="mx-auto mb-4 bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-tree text-danger" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title">Eco-Friendly</h5>
                            <p class="card-text">Reduce paper waste and environmental impact while creating beautiful invitations.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="p-4 text-center border-0 shadow-sm card h-100 fade-in-up" style="animation-delay: 0.7s;">
                        <div class="card-body">
                            <div class="mx-auto mb-4 bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="card-title">Real-Time Updates</h5>
                            <p class="card-text">Update event details instantly, and all guests receive immediate notifications.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Themes Section -->
    <section id="themes" class="py-5">
        <div class="container">
            <h2 class="section-title fade-in-up">Beautiful Digital Themes</h2>
            <p class="mb-5 text-center text-muted fade-in-up" style="animation-delay: 0.1s;">Explore our collection of elegant digital wedding invitation themes designed to match your style</p>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="theme-card fade-in-up" style="animation-delay: 0.2s;">
                        <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&h=400&q=80" alt="Elegant Theme">
                        <div class="p-4">
                            <h5 class="mb-3">Elegant Theme</h5>
                            <p class="mb-0 text-muted">Classic and sophisticated design with beautiful typography</p>
                        </div>
                        <div class="theme-overlay">
                            <h6 class="mb-0">Elegant Theme</h6>
                            <p class="mb-0">Click to preview</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="theme-card fade-in-up" style="animation-delay: 0.3s;">
                        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&h=400&q=80" alt="Modern Theme">
                        <div class="p-4">
                            <h5 class="mb-3">Modern Theme</h5>
                            <p class="mb-0 text-muted">Clean lines and contemporary design for the modern couple</p>
                        </div>
                        <div class="theme-overlay">
                            <h6 class="mb-0">Modern Theme</h6>
                            <p class="mb-0">Click to preview</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="theme-card fade-in-up" style="animation-delay: 0.4s;">
                        <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&h=400&q=80" alt="Vintage Theme">
                        <div class="p-4">
                            <h5 class="mb-3">Vintage Theme</h5>
                            <p class="mb-0 text-muted">Timeless charm with vintage elements and classic aesthetics</p>
                        </div>
                        <div class="theme-overlay">
                            <h6 class="mb-0">Vintage Theme</h6>
                            <p class="mb-0">Click to preview</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="theme-card fade-in-up" style="animation-delay: 0.5s;">
                        <img src="https://images.unsplash.com/photo-1510089142880-77c7e020a4f6?auto=format&fit=crop&w=600&h=400&q=80" alt="Romantic Theme">
                        <div class="p-4">
                            <h5 class="mb-3">Romantic Theme</h5>
                            <p class="mb-0 text-muted">Soft colors and floral elements for a romantic atmosphere</p>
                        </div>
                        <div class="theme-overlay">
                            <h6 class="mb-0">Romantic Theme</h6>
                            <p class="mb-0">Click to preview</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="theme-card fade-in-up" style="animation-delay: 0.6s;">
                        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&h=400&q=80" alt="Minimalist Theme">
                        <div class="p-4">
                            <h5 class="mb-3">Minimalist Theme</h5>
                            <p class="mb-0 text-muted">Simple and elegant design focusing on essential elements</p>
                        </div>
                        <div class="theme-overlay">
                            <h6 class="mb-0">Minimalist Theme</h6>
                            <p class="mb-0">Click to preview</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="theme-card fade-in-up" style="animation-delay: 0.7s;">
                        <img src="https://images.unsplash.com/photo-1510089142880-77c7e020a4f6?auto=format&fit=crop&w=600&h=400&q=80" alt="Bohemian Theme">
                        <div class="p-4">
                            <h5 class="mb-3">Bohemian Theme</h5>
                            <p class="mb-0 text-muted">Free-spirited design with natural elements and earthy tones</p>
                        </div>
                        <div class="theme-overlay">
                            <h6 class="mb-0">Bohemian Theme</h6>
                            <p class="mb-0">Click to preview</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="packages" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title fade-in-up">Our Digital Packages</h2>
            <p class="mb-5 text-center text-muted fade-in-up" style="animation-delay: 0.1s;">Choose the perfect package for your digital invitation needs</p>
            
            <div class="row g-4">
                @forelse($packages as $package)
                @php
                    $maxPrice = $packages->max('price');
                    $isPopular = $package->price == $maxPrice && $packages->count() > 1;
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="package-card @if($isPopular) popular @endif border p-4 h-100 fade-in-up" style="animation-delay: {{ 0.2 + ($loop->index * 0.1) }}s;">
                        <div class="mb-4 text-center">
                            <h5 class="mb-3">{{ $package->name }}</h5>
                            <h3 class="mb-3 text-primary fw-bold">Rp {{ number_format($package->price, 0, ',', '.') }}</h3>
                            <p class="mb-0 text-muted">{{ $package->description }}</p>
                        </div>
                        <ul class="mb-4 list-group list-group-flush">
                            <li class="px-0 list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> {{ $package->period }} Days Access</li>
                            <li class="px-0 list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> {{ $package->name === 'Ekonomis' ? '1 Theme' : 'Unlimited Themes' }}</li>
                            <li class="px-0 list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> RSVP Management</li>
                            <li class="px-0 list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Gallery Integration</li>
                            <li class="px-0 list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> {{ $package->name === 'Ekonomis' ? 'Email' : 'Priority' }} Support</li>
                        </ul>
                        <div class="text-center">
                            <a href="{{ route('register') }}" class="btn @if($isPopular) btn-primary @else btn-outline-primary @endif w-100 rounded-2" 
                               data-package="{{ $package->name }}" 
                               data-price="Rp {{ number_format($package->price, 0, ',', '.') }}">Choose Plan</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="py-5 text-center fade-in-up">
                        <h5>No packages available at this time</h5>
                        <p class="text-muted">Please check back later for our packages</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section id="register" class="py-5">
        <div class="container">
            <h2 class="section-title fade-in-up">Create Your Digital Invitation</h2>
            <p class="mb-5 text-center text-muted fade-in-up" style="animation-delay: 0.1s;">Start creating your beautiful digital wedding invitation today</p>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="registration-form fade-in-up" style="animation-delay: 0.2s;">
                        <form method="POST" action="{{ route('register') }}" id="registrationForm">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="firstName" name="first_name" placeholder="Enter your first name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="lastName" name="last_name" placeholder="Enter your last name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                                <div class="form-text">We'll never share your email with anyone else.</div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="Enter your phone number" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Create a password" required>
                                    <div class="form-text">Use 8 or more characters with a mix of letters, numbers & symbols</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="couple_name" class="form-label">Couple's Names</label>
                                <input type="text" class="form-control @error('couple_name') is-invalid @enderror" id="couple_name" name="couple_name" placeholder="Enter the names of the couple (e.g., John & Jane)" value="{{ old('couple_name') }}" required>
                                @error('couple_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="wedding_date" class="form-label">Expected Wedding Date</label>
                                <input type="date" class="form-control @error('wedding_date') is-invalid @enderror" id="wedding_date" name="wedding_date" value="{{ old('wedding_date') }}" required>
                                @error('wedding_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" value="1" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> and <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="overflow-hidden btn btn-primary btn-lg rounded-2 position-relative">
                                    <span class="position-relative">Create Digital Invitation</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 text-white bg-dark">
        <div class="container">
            <div class="row">
                <div class="mb-4 col-lg-4 mb-lg-0">
                    <img src="{{ asset('images/root/blessed_day.png') }}" alt="Logo" height="60" class="mb-3">
                    <p class="text-light">Creating beautiful digital wedding invitations that capture your love story and connect with guests instantly.</p>
                    <div class="gap-3 mt-3 d-flex">
                        <a href="#" class="text-light fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light fs-4"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light fs-4"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light fs-4"><i class="bi bi-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="mb-4 col-lg-2 col-md-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#digital-features" class="text-light text-decoration-none">Features</a></li>
                        <li><a href="#themes" class="text-light text-decoration-none">Themes</a></li>
                        <li><a href="#packages" class="text-light text-decoration-none">Packages</a></li>
                        <li><a href="#register" class="text-light text-decoration-none">Register</a></li>
                    </ul>
                </div>
                
                <div class="mb-4 col-lg-3 col-md-4 mb-md-0">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Help Center</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Contact Us</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Privacy Policy</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope me-2"></i> support@blessedday.com</li>
                        <li><i class="bi bi-telephone me-2"></i> +62 123 4567</li>
                        <li><i class="bi bi-geo-alt me-2"></i> Jakarta, Indonesia</li>
                    </ul>
                    <div class="mt-3">
                        <h6>Newsletter</h6>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email">
                            <button class="btn btn-outline-light" type="button">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center">
                <p class="mb-0">&copy; 2025 Blessed Day Digital Invitations. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>By using our services, you agree to the following terms and conditions:</p>
                    <ul>
                        <li>You will use our platform responsibly and in accordance with applicable laws</li>
                        <li>You are responsible for maintaining the confidentiality of your account</li>
                        <li>We reserve the right to modify or discontinue our services at any time</li>
                        <li>All content created with our platform remains your property</li>
                        <li>We are not responsible for any issues related to invitation delivery</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>We respect your privacy and are committed to protecting your personal data:</p>
                    <ul>
                        <li>We collect only the information necessary to provide our services</li>
                        <li>We use industry-standard security measures to protect your data</li>
                        <li>We never sell your personal information to third parties</li>
                        <li>You can request deletion of your data at any time</li>
                        <li>We use cookies to improve your experience on our platform</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all elements with fade-in-up class
        document.querySelectorAll('.fade-in-up').forEach(el => {
            observer.observe(el);
        });

        // Package selection feedback
        document.querySelectorAll('.btn[data-package]').forEach(btn => {
            btn.addEventListener('click', function() {
                const packageName = this.getAttribute('data-package');
                const price = this.getAttribute('data-price');
                
                // Show a temporary notification
                const notification = document.createElement('div');
                notification.className = 'position-fixed top-0 end-0 p-3';
                notification.style.zIndex = '9999';
                notification.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header">
                            <strong class="me-auto">Package Selected</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            You've selected the <strong>${packageName}</strong> package (${price})
                        </div>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Remove notification after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 5000);
            });
        });

        // Form validation feedback
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });

        // Add floating animation to theme cards on load
        document.querySelectorAll('.theme-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.animation = 'none';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.animation = '';
            });
        });
    </script>
</body>
</html>