<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FunShirt �� Loja Online</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- Guest Hero Section --}}
        @guest
        <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 min-h-screen flex flex-col">
            {{-- Navigation --}}
            <header class="w-full px-6 py-5">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="h-10 w-10 text-white" viewBox="0 0 64 64" fill="none">
                            <rect width="64" height="64" rx="14" fill="white" fill-opacity="0.2"/>
                            <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-size="32" fill="white" font-family="Arial">??</text>
                        </svg>
                        <span class="text-2xl font-bold text-white tracking-tight">FunShirt</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-white/90 hover:text-white px-4 py-2 text-sm font-medium transition">Entrar</a>
                        <a href="{{ route('register') }}" class="bg-white text-indigo-600 hover:bg-indigo-50 px-5 py-2 rounded-full text-sm font-semibold shadow-lg transition">Criar Conta</a>
                    </div>
                </div>
            </header>

            {{-- Hero --}}
            <div class="flex-1 flex items-center justify-center px-6">
                <div class="max-w-3xl text-center">
                    <h1 class="text-5xl sm:text-6xl font-extrabold text-white leading-tight tracking-tight">
                        A tua <span class="text-yellow-300">t-shirt</span>,<br>o teu estilo.
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-white/80 leading-relaxed max-w-2xl mx-auto">
                        Na FunShirt encontras t-shirts estampadas para todas as ocasiões. Escolhe designs do catálogo ou envia a tua própria imagem. Personaliza, encomenda e recebe à tua porta.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('register') }}" class="bg-yellow-400 text-gray-900 hover:bg-yellow-300 px-8 py-3 rounded-full text-lg font-bold shadow-xl transition">
                            Começar Agora
                        </a>
                        <a href="{{ route('login') }}" class="border-2 border-white/40 text-white hover:bg-white/10 px-8 py-3 rounded-full text-lg font-semibold transition">
                            Já tenho conta
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <footer class="py-6 text-center text-sm text-white/50">
                FunShirt &mdash; Aplicações para a Internet 2025/26
            </footer>
        </div>
        @endguest

        {{-- Authenticated Dashboard --}}
        @auth
        <div class="min-h-screen bg-gray-50">
            {{-- Top Nav --}}
            <livewire:layout.navigation />

            {{-- Page Content --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                {{-- Welcome --}}
                <div class="mb-10">
                    <h1 class="text-3xl font-bold text-gray-900">Bem-vindo, {{ Auth::user()->name }}!</h1>
                    <p class="mt-1 text-gray-500">Painel principal da FunShirt</p>
                </div>

                {{-- Feature Groups Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                    {{-- G1: ��֤���û����� (20%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-emerald-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G1 �� Autentica??o & Utilizadores</h2>
                        </div>
                        <div class="p-5 space-y-3">
                            <a href="{{ route('profile') }}" wire:navigate class="block px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-medium hover:bg-emerald-100 transition">
                                ?? Perfil �� show, edit
                            </a>
                            <a href="{{ route('dashboard') }}" wire:navigate class="block px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 text-sm font-medium hover:bg-emerald-100 transition">
                                ?? Dashboard
                            </a>
                            @if(Auth::user()->isAdmin())
                            <div class="px-3 py-2 text-gray-500 text-xs border-t border-gray-100 pt-3">
                                <span class="font-semibold text-gray-700">Administrador:</span><br>
                                Utilizadores (Staff/Admin) �� index, create, edit, block, delete<br>
                                Clientes �� index, block, soft-delete
                            </div>
                            @endif
                            @if(Auth::user()->isStaff())
                            <div class="px-3 py-2 text-gray-500 text-xs border-t border-gray-100 pt-3">
                                <span class="font-semibold text-gray-700">Funcion��rio:</span><br>
                                Alterar senha
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- G2: Ŀ¼ Catalog (20%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-blue-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G2 �� Cat��logo</h2>
                        </div>
                        <div class="p-5 space-y-2 text-sm">
                            <div class="px-3 py-2 bg-blue-50 rounded-lg text-blue-700 font-medium">??? Cat��logo �� index, show, filter</div>
                            @if(Auth::user()->isAdmin())
                            <div class="px-3 py-2 text-gray-500">
                                <span class="text-gray-600 font-medium">Categorias:</span> index, create, edit, delete<br>
                                <span class="text-gray-600 font-medium">Cores:</span> index, create, edit, delete<br>
                                <span class="text-gray-600 font-medium">Pre?os:</span> edit
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- G3: ���ﳵ Cart (20%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-amber-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G3 �� Carrinho</h2>
                        </div>
                        <div class="p-5 text-sm">
                            <div class="px-3 py-2 bg-amber-50 rounded-lg text-amber-700 font-medium">?? Carrinho �� index, create, update, remove, clear</div>
                            <div class="px-3 py-2 mt-2 text-gray-500 text-xs">
                                Carrinho mantido em sess?o.<br>
                                Dispon��vel para utilizadores an��nimos e clientes.
                            </div>
                        </div>
                    </div>

                    {{-- G4: ���� Orders (20%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-rose-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G4 �� Encomendas</h2>
                        </div>
                        <div class="p-5 space-y-2 text-sm">
                            @if(Auth::user()->isCustomer())
                            <div class="px-3 py-2 bg-rose-50 rounded-lg text-rose-700 font-medium">?? As Minhas Encomendas �� index, show</div>
                            <div class="px-3 py-2 bg-rose-50 rounded-lg text-rose-700 font-medium">? Checkout �� create, confirm</div>
                            @endif
                            @if(Auth::user()->isStaff())
                            <div class="px-3 py-2 text-gray-500">
                                <span class="text-gray-600 font-medium">Funcion��rio:</span> Encomendas pendentes �� index, update (�� closed)
                            </div>
                            @endif
                            @if(Auth::user()->isAdmin())
                            <div class="px-3 py-2 text-gray-500">
                                <span class="text-gray-600 font-medium">Administrador:</span> Todas as encomendas �� index, show, update (�� closed/canceled)
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- G5: ˽��ͼƬ (5%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-violet-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G5 �� Imagens Pr��prias</h2>
                        </div>
                        <div class="p-5 text-sm">
                            @if(Auth::user()->isCustomer())
                            <div class="px-3 py-2 bg-violet-50 rounded-lg text-violet-700 font-medium">??? As Minhas Imagens �� index, upload, delete</div>
                            @else
                            <div class="px-3 py-2 text-gray-400 italic">Apenas para clientes</div>
                            @endif
                        </div>
                    </div>

                    {{-- G6: �վݺ��ʼ� (5%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-teal-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G6 �� Recibos & Emails</h2>
                        </div>
                        <div class="p-5 text-sm">
                            @if(Auth::user()->isCustomer())
                            <div class="px-3 py-2 bg-teal-50 rounded-lg text-teal-700 font-medium">?? Recibos PDF �� show, download</div>
                            @endif
                            <div class="px-3 py-2 mt-2 text-gray-500 text-xs">
                                Envio autom��tico de emails:<br>
                                pending, closed (com PDF), canceled
                            </div>
                        </div>
                    </div>

                    {{-- G7: T-shirt Ԥ�� (5%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-orange-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G7 �� Preview T-shirt</h2>
                        </div>
                        <div class="p-5 text-sm">
                            <div class="px-3 py-2 bg-orange-50 rounded-lg text-orange-700 font-medium">?? Preview �� show</div>
                            <div class="px-3 py-2 mt-2 text-gray-500 text-xs">
                                Visualiza??o da t-shirt com a imagem,<br>cor e tamanho selecionados.
                            </div>
                        </div>
                    </div>

                    {{-- G8: ͳ�� Statistics (5%) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-cyan-600 px-5 py-3">
                            <h2 class="text-white font-bold text-sm uppercase tracking-wider">G8 �� Estat��sticas</h2>
                        </div>
                        <div class="p-5 text-sm">
                            @if(Auth::user()->isAdmin())
                            <div class="px-3 py-2 bg-cyan-50 rounded-lg text-cyan-700 font-medium">?? Painel Estat��stico �� index</div>
                            <div class="px-3 py-2 mt-2 text-gray-500 text-xs">
                                Total vendas, encomendas por m��s,<br>top imagens, top clientes, etc.
                            </div>
                            @else
                            <div class="px-3 py-2 text-gray-400 italic">Apenas para administradores</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endauth
    </body>
</html>