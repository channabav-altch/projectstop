<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STOCK.PRO | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    {{-- <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style> --}}

   <style>
    /* ធ្វើឲ្យគ្រប់ Input, Select និង Textarea ធំទាំងអស់ */
    input, select, textarea {
        font-size: 20px !important;      /* ធំជាងមុន */
        padding: 15px 22px !important;   /* បន្ថែមចន្លោះជុំវិញអក្សរ ធ្វើឲ្យប្រអប់មើលទៅធំស្អាត */
        height: auto !important;         /* បើកសេរីភាពកម្ពស់ឲ្យវាដុះតាមទំហំអក្សរ */
        line-height: 1.5 !important;     /* ជួយឲ្យមានចន្លោះបន្ទាត់មិនបៀតគ្នា */
    }

    /* ធ្វើឲ្យ Label (អក្សរលើប្រអប់) ធំទៅតាមហ្នឹងដែរ */
    label {
        font-size: 16px !important;
        font-weight: 600 !important;
        margin-bottom: 15px !important;
    }
</style>
    <!-- ភ្ជាប់ Font Kantumruy Pro ពី Google -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#0B132B] text-slate-100 antialiased m-0 p-0 selection:bg-cyan-500 selection:text-white">

    <div class="min-h-screen flex">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('layouts.navigation')

            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>
