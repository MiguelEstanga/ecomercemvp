<div class="sidebar w-64 bg-gray-800 text-white flex-shrink-0 hidden md:block">
    <div class="p-6 text-xl font-semibold border-b border-gray-700">
        <a href="#" class="text-white hover:text-indigo-400">Admin Panel</a>
    </div>

    <nav class="mt-6">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white 
                  @if (request()->routeIs('admin.dashboard')) bg-gray-900 text-white @endif">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-10v10a1 1 0 001 1h3M14 10v7a1 1 0 01-1 1h-2a1 1 0 01-1-1v-7">
                </path>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('order.admin.index') }}"
            class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white @if (request()->routeIs('order.admin.index')) bg-gray-900 text-white @endif">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5h6">
                </path>
            </svg>
            Órdenes
        </a>

        <a href="{{ route('admin.productos') }}"
            class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white
            @if (request()->routeIs('admin.productos')) bg-gray-900 text-white @endif
            ">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292m0 0a4 4 0 000 5.292M12 4.354a4 4 0 100 5.292m0 0a4 4 0 110 5.292M12 10.5h.01M12 16.5h.01">
                </path>
            </svg>
            Productos
        </a>

        <a href="{{ route('user.admin.index') }}"
            class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white @if (request()->routeIs('user.admin.index')) bg-gray-900 text-white @endif">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Usuarios
        </a>
        <a href="{{ route('contact.admin.index') }}"
            class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white @if (request()->routeIs('contact.admin.index')) bg-gray-900 text-white @endif">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Contacto
        </a>

        <a href="{{ route('agency.admin.index') }}"
            class="flex items-center py-2 px-6 text-gray-400 hover:bg-gray-700 hover:text-white @if (request()->routeIs('agency.admin.index')) bg-gray-900 text-white @endif">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Agencias
        </a>
    </nav>
</div>
