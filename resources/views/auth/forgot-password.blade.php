@extends('layouts.auth-layout', ['title' => 'Forgot Password'])

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Mot de passe oublié</h2>

    @if(session('status'))
        <div class="bg-green-100 text-green-600 p-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 text-gray-600">Email</label>
            <input type="email" name="email" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Envoyer le lien de réinitialisation</button>
    </form>

    <p class="text-sm text-center mt-4">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Retour à la connexion</a>
    </p>
@endsection