<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-8 px-4">
        <!-- Contenedor principal -->
        <div class="bg-white shadow-lg rounded-lg max-w-2xl w-full overflow-hidden flex flex-col md:flex-row">
            <!-- Izquierda: Imagen (oculta en pantallas muy pequeñas) -->
            <div class="hidden md:block md:w-1/3">
                <img 
                    src="images/caballo.jpg" 
                    alt="Placeholder Image" 
                    class="object-cover w-full h-full"
                >
            </div>

            <x-alerts />

            <!-- Derecha: Formulario de Login -->
            <div class="w-full md:w-2/3 py-8 px-4 md:px-8 max-w-md mx-auto">
                <h1 class="text-3xl font-semibold mt-6 text-center">Iniciar sesion</h1>
                
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- Email Input -->
                    <div class="mb-4 px-2">
                        <label for="email" class="block text-gray-700 font-medium">Correo</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            class="mt-1 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
                            autocomplete="email" 
                            value="{{ old('email') }}" 
                            required
                        >
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4 px-2">
                        <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="mt-1 w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:border-blue-500"
                            autocomplete="current-password" 
                            required
                        >
                    </div>

                    <!-- Login Button -->
                    <div class="px-2">
                        <button 
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full"
                        >
                            Entrar
                        </button>
                    </div>
                </form>

                <!-- Sign up Link -->
                <div class="mt-4 mb-4 text-center">
                    <a href="{{ route('register') }}" class="text-blue-500 hover:underline">
                        No tienes una cuenta? Regístrate
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
