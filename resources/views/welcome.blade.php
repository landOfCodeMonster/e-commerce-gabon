<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iCommerce Gabon - Achetez partout, recevez au Gabon</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    {{-- Navbar --}}
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <span class="text-2xl font-bold text-indigo-600">iCommerce Gabon</span>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Tableau de bord</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">S'inscrire</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6">
                Achetez partout dans le monde,<br>
                <span class="text-indigo-200">recevez au Gabon</span>
            </h1>
            <p class="text-xl text-indigo-100 max-w-2xl mx-auto mb-8">
                Collez simplement le lien du produit que vous souhaitez depuis n'importe quel site (Amazon, AliExpress, etc.) et nous nous occupons du reste.
            </p>
            @guest
                <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-indigo-50 transition">
                    Commencer maintenant
                </a>
            @else
                <a href="{{ route('client.orders.create') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-indigo-50 transition">
                    Passer une commande
                </a>
            @endguest
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Comment ça marche ?</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                    <h3 class="font-semibold text-lg mb-2">Copiez le lien</h3>
                    <p class="text-gray-500">Trouvez le produit sur le site de votre choix et copiez son lien URL.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                    <h3 class="font-semibold text-lg mb-2">Collez et commandez</h3>
                    <p class="text-gray-500">Collez le lien dans notre formulaire, choisissez la quantité et la couleur.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                    <h3 class="font-semibold text-lg mb-2">Payez en ligne</h3>
                    <p class="text-gray-500">Payez par carte bancaire (Visa/Mastercard) ou PayPal en toute sécurité.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">4</div>
                    <h3 class="font-semibold text-lg mb-2">Recevez au Gabon</h3>
                    <p class="text-gray-500">Suivez votre commande en temps réel et recevez-la chez vous.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Pourquoi nous choisir ?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="text-3xl mb-3">&#128666;</div>
                    <h3 class="font-semibold text-lg mb-2">Suivi en 6 étapes</h3>
                    <p class="text-gray-500">Suivez votre commande de l'achat à la livraison avec notre système de suivi détaillé.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="text-3xl mb-3">&#128179;</div>
                    <h3 class="font-semibold text-lg mb-2">Paiement sécurisé</h3>
                    <p class="text-gray-500">Payez avec Visa, Mastercard ou PayPal. Vos transactions sont 100% sécurisées.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="text-3xl mb-3">&#128196;</div>
                    <h3 class="font-semibold text-lg mb-2">Facture automatique</h3>
                    <p class="text-gray-500">Recevez automatiquement votre facture PDF à la livraison de votre commande.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-400 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-lg font-semibold text-white mb-2">iCommerce Gabon</p>
            <p>Libreville, Gabon</p>
            <p class="mt-4 text-sm">&copy; {{ date('Y') }} iCommerce Gabon. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
