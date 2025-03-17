@extends('layouts.auth-layout')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Créer un compte</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1 text-gray-600">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div>
            <label class="block mb-1 text-gray-600">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div>
            <label class="block mb-1 text-gray-600">Mot de passe</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div>
            <label class="block mb-1 text-gray-600">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">S'inscrire</button>
    </form>

    <p class="text-sm text-center mt-4">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
    </p>
@endsection
