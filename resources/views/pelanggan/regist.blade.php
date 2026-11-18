@extends('layouts.main')

@section('title', 'Daftar Akun Baru — Plan2Eat')
@section('hide_nav', true)
@section('body_class', 'auth-shell')

@section('content')
<main class="login-page-user">
  <section class="authentication">

    <div class="brand-header">
      <div class="plan2eat-brand-lockup" aria-label="Plan2Eat - Plan It, Order It, Irit">
        <svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="7" width="40" height="36" rx="10" fill="#05371F"/>
          <rect x="13" y="3" width="4" height="8" rx="2" fill="#05371F"/>
          <rect x="29" y="3" width="4" height="8" rx="2" fill="#05371F"/>
          <rect x="14.2" y="4.2" width="1.6" height="5.6" rx="0.8" fill="#FFFFFF"/>
          <rect x="30.2" y="4.2" width="1.6" height="5.6" rx="0.8" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="11.5" fill="#FFFFFF"/>
          <circle cx="23" cy="25" r="6.8" fill="#E8702A"/>
        </svg>

        <div class="brand-wordmark-col">
          <div class="brand-wordmark">
            <span class="word-dark">Plan</span><span class="word-orange">2</span><span class="word-dark">Eat</span>
          </div>
          <span class="brand-tagline">PLAN IT, ORDER IT, IRIT</span>
        </div>
      </div>
    </div>

    <div class="auth-heading">
      <h1 class="text-wrapper">Daftar Akun Baru</h1>
      <p class="subtitle">Silakan masukkan detail akun Anda di bawah ini</p>
    </div>

    @if ($errors->any())
      <div class="auth-error">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form class="auth-form" action="{{ route('register') }}" method="POST">
      @csrf

      {{-- Role pelanggan ditentukan dari halaman registrasi ini --}}
      <input type="hidden" name="role" value="pelanggan">

      <div class="form-group-custom">
        <label for="name" class="label">Nama Lengkap</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input
              type="text"
              id="name"
              name="name"
              class="container-2"
              placeholder="Nama lengkap"
              value="{{ old('name') }}"
              required
              autocomplete="name"
            >
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="email" class="label">Email</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="4" width="20" height="16" rx="3"/>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input
              type="email"
              id="email"
              name="email"
              class="container-2"
              placeholder="joe@gmail.com"
              value="{{ old('email') }}"
              required
              autocomplete="email"
            >
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="password" class="label">Password</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="3"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              <circle cx="12" cy="16" r="1" fill="#1C1C11"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input
              type="password"
              id="password"
              name="password"
              class="container-2"
              placeholder="***************"
              required
              autocomplete="new-password"
            >
          </div>
        </div>
      </div>

      <div class="form-group-custom">
        <label for="password_confirmation" class="label">Konfirmasi Password</label>
        <div class="phone-OTP-input">
          <div class="background">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1C1C11" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="11" width="18" height="11" rx="3"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              <circle cx="12" cy="16" r="1" fill="#1C1C11"/>
            </svg>
          </div>
          <div class="input-wrapper">
            <input
              type="password"
              id="password_confirmation"
              name="password_confirmation"
              class="container-2"
              placeholder="***************"
              required
              autocomplete="new-password"
            >
          </div>
        </div>
      </div>

      <button type="submit" class="action-primary">
        <span class="text-wrapper-4">Lanjutkan</span>
      </button>
    </form>

    <div class="auth-card-bottom-line"></div>
  </section>

  <div class="container-wrapper">
    <p class="belum-punya-akun">
      Sudah Punya Akun?
      <a href="{{ route('login_page') }}" class="text-wrapper-5">Login</a>
    </p>
  </div>
</main>
@endsection