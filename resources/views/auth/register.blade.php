<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-8 px-4">
        <div class="bg-white shadow-lg rounded-lg max-w-2xl w-full overflow-hidden flex flex-col md:flex-row">
            <div class="hidden md:block md:w-1/3">
                <img 
                    src="images/caballo.jpg" 
                    alt="Placeholder Image" 
                    class="object-cover w-full h-full"
                >
            </div>

            <x-alerts />

            <div class="w-full md:w-2/3 py-8 px-4 md:px-8 max-w-md mx-auto">
                <h1 class="text-3xl font-semibold mt-6 text-center">Crear cuenta</h1>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="mb-4 px-2">
                        <label for="name" class="block text-gray-700 font-medium">Nombre</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required autofocus 
                            autocomplete="name"
                            class="mt-1 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
                        >
                    </div>

                    <div class="mb-4 px-2">
                        <label for="email" class="block text-gray-700 font-medium">Correo</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            class="mt-1 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
                        >
                    </div>

                    <div class="mb-4 px-2">
                        <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="mt-1 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
                        >
                    </div>

                    <div class="px-2">
                        <button 
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full"
                        >
                            Registrar
                        </button>
                    </div>
                </form>

                <!-- Link a Login -->
                <div class="mt-4 mb-4 text-center">
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                        ¿Ya tienes una cuenta? Inicia sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
