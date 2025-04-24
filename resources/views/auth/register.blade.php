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


@section('scripts')

<script>
document.querySelector('form').addEventListener('submit', function (e) {
    const nameInput = document.querySelector('input[name="name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');

    const nameRegex = /^[a-zA-ZÀ-ÿ\s\-]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    let errors = [];

    if (!nameRegex.test(nameInput.value)) {
        errors.push("The name must contain only letters, spaces and hyphens.");
    }

    if (!emailRegex.test(emailInput.value)) {
        errors.push("The email address is not valid.");
    }

    if (!passwordRegex.test(passwordInput.value)) {
        errors.push("The password must contain at least one uppercase letter, one lowercase letter, one number, and be at least 8 characters long.");
    }

    if (passwordInput.value !== confirmPasswordInput.value) {
        errors.push("The passwords do not match.");
    }

    if (errors.length > 0) {
        e.preventDefault();

        let errorDiv = document.createElement('div');
        errorDiv.className = "bg-red-100 text-red-600 p-3 rounded mb-4";

        let ul = document.createElement('ul');
        ul.className = "list-disc list-inside text-sm";

        errors.forEach(error => {
            let li = document.createElement('li');
            li.textContent = error;
            ul.appendChild(li);
        });

        errorDiv.appendChild(ul);


        let existingError = document.querySelector('form .bg-red-100');
        if (existingError) existingError.remove();

        document.querySelector('form').prepend(errorDiv);
    }
});
</script>
