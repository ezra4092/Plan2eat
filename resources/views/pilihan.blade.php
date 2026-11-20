<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pilih Peran Registrasi | Plan2Eat</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="{{ asset('template/css/globals.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

    <style>
        .pilihan-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .pilihan-wrapper {
            width: 100%;
            max-width: 720px;
            background: F8F3EA;
            border-radius: 28px;
            padding: 36px 28px;
            box-shadow: 0 12px 36px rgba(5, 55, 31, 0.08);
            border: 1px solid #ECECEC;
        }
        .pilihan-logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }
        .pilihan-logo-wrap img {
            max-height: 54px;
            width: auto;
            object-fit: contain;
        }
        .pilihan-heading {
            text-align: center;
            margin-bottom: 28px;
        }
        .pilihan-heading h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #16442B;
            margin: 0 0 8px;
        }
        .pilihan-heading p {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #6B7280;
            margin: 0;
        }
        .pilihan-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }
        @media (max-width: 640px) {
            .pilihan-wrapper {
                padding: 28px 18px;
                border-radius: 22px;
            }
            .pilihan-grid {
                grid-template-columns: 1fr;
            }
        }
        .pilihan-card-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: #FAFBF9;
            border: 2px solid #E5E7EB;
            border-radius: 22px;
            padding: 26px 20px;
            cursor: pointer;
            transition: all 0.22s ease;
            font-family: inherit;
            width: 100%;
            position: relative;
        }
        .pilihan-card-btn:hover {
            border-color: #16442B;
            background: #F0FDF4;
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(22, 68, 43, 0.1);
        }
        .pilihan-card-visual {
            width: 120px;
            height: 120px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            transition: transform 0.22s ease;
        }
        .pilihan-card-btn:hover .pilihan-card-visual {
            transform: scale(1.06);
        }
        .pilihan-card-visual.is-pelanggan {
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
        }
        .pilihan-card-visual.is-umkm {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        }
        .pilihan-card-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: #16442B;
            margin: 0 0 8px;
        }
        .pilihan-card-desc {
            font-size: 13px;
            color: #4B5563;
            line-height: 1.5;
            margin: 0 0 18px;
        }
        .pilihan-card-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 999px;
            background: #16442B;
            color: #FFFFFF;
            font-size: 12.5px;
            font-weight: 700;
            margin-top: auto;
        }
        .pilihan-alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 18px;
            text-align: center;
        }
        .pilihan-footer {
            text-align: center;
            font-size: 13.5px;
            color: #4B5563;
        }
        .pilihan-footer a {
            color: #16442B;
            font-weight: 700;
            text-decoration: none;
        }
        .pilihan-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="login-page-body">
    <main class="pilihan-shell">
        <section class="pilihan-wrapper" aria-labelledby="pilihan-heading">

            {{-- Logo Plan2Eat di atas kedua card --}}
            <div class="pilihan-logo-wrap">
                <img src="{{ asset('images/Logo 1.png') }}" alt="Plan2Eat - Plan It, Order It, Irit">
            </div>

            <div class="pilihan-heading">
                <h1 id="pilihan-heading">Bergabung Bersama Plan2Eat</h1>
                <p>Pilih jenis akun yang ingin Anda daftarkan terlebih dahulu</p>
            </div>

            @if ($errors->any())
                <div class="pilihan-alert-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form id="pilihanRoleForm" action="{{ route('register.form') }}" method="GET">
                <input type="hidden" name="role" id="selectedRoleInput" value="">

                <div class="pilihan-grid">
                    {{-- Card 1: Register sebagai pelanggan --}}
                    <button type="button" class="pilihan-card-btn" data-role="pelanggan">
                        <div class="pilihan-card-visual is-pelanggan" aria-hidden="true">
                            {{-- Ilustrasi Pelanggan & Makanan Sehat --}}
                            <svg width="72" height="72" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="32" cy="36" r="20" fill="#FFFFFF" stroke="#05371F" stroke-width="2.5"/>
                                <path d="M16 36C16 27.163 23.163 20 32 20C40.837 20 48 27.163 48 36H16Z" fill="#10B981"/>
                                <circle cx="26" cy="30" r="3" fill="#F59E0B"/>
                                <circle cx="37" cy="29" r="3.5" fill="#EF4444"/>
                                <path d="M22 42H42" stroke="#05371F" stroke-width="2.5" stroke-linecap="round"/>
                                <path d="M27 14C27 11 29 9 32 9C35 9 37 11 37 14" stroke="#05371F" stroke-width="2.5" stroke-linecap="round"/>
                                <circle cx="49" cy="17" r="7" fill="#05371F"/>
                                <path d="M46 17L48 19L52.5 14.5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h2 class="pilihan-card-title">Register sebagai pelanggan</h2>
                        <p class="pilihan-card-desc">
                            Temukan paket katering sehat harian, atur preferensi diet, dan nikmati promo flash sale malam.
                        </p>
                        <span class="pilihan-card-cta">
                            <span>Daftar Pelanggan</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </button>

                    {{-- Card 2: Register sebagai UMKM --}}
                    <button type="button" class="pilihan-card-btn" data-role="umkm">
                        <div class="pilihan-card-visual is-umkm" aria-hidden="true">
                            {{-- Ilustrasi Mitra Dapur UMKM --}}
                            <svg width="72" height="72" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="12" y="26" width="40" height="26" rx="4" fill="#FFFFFF" stroke="#05371F" stroke-width="2.5"/>
                                <path d="M10 26L15 14H49L54 26H10Z" fill="#E8702A" stroke="#05371F" stroke-width="2.5" stroke-linejoin="round"/>
                                <path d="M10 26C10 29.5 13 31.5 16.5 31.5C20 31.5 22 29.5 22 26C22 29.5 25 31.5 28.5 31.5C32 31.5 34 29.5 34 26C34 29.5 37 31.5 40.5 31.5C44 31.5 46 29.5 46 26C46 29.5 49 31.5 51.5 31.5C53.5 31.5 54 29.5 54 26" stroke="#05371F" stroke-width="2"/>
                                <rect x="25" y="37" width="14" height="15" rx="2" fill="#D1FAE5" stroke="#05371F" stroke-width="2"/>
                                <circle cx="35" cy="45" r="1.5" fill="#05371F"/>
                            </svg>
                        </div>
                        <h2 class="pilihan-card-title">Register sebagai UMKM</h2>
                        <p class="pilihan-card-desc">
                            Daftarkan dapur katering Anda, kelola menu &amp; pesanan harian, serta jangkau lebih banyak mahasiswa.
                        </p>
                        <span class="pilihan-card-cta">
                            <span>Daftar Mitra UMKM</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            <div class="pilihan-footer">
                Sudah Punya Akun? <a href="{{ route('login_page') }}">Masuk di sini</a>
            </div>
        </section>
    </main>

    <script>
        document.querySelectorAll('.pilihan-card-btn').forEach(function (card) {
            card.addEventListener('click', function () {
                const role = this.dataset.role;
                if (role !== 'pelanggan' && role !== 'umkm') {
                    alert('Role pendaftaran tidak valid. Silakan pilih Pelanggan atau UMKM.');
                    return;
                }
                document.getElementById('selectedRoleInput').value = role;
                document.getElementById('pilihanRoleForm').submit();
            });
        });
    </script>
</body>
</html>
