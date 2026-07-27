<nav x-data="{ open: false }" class="bg-[#0B132B]/90 backdrop-blur-md border-b border-[#1C2C4E]/60 text-slate-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex items-center">
                <button @click="open = ! open" class="text-slate-400 hover:text-white md:hidden p-2 rounded-xl bg-[#111C38] border border-[#1C2C4E]">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>

            <div class="flex items-center ms-auto">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3.5 py-2 border border-[#1C2C4E] rounded-xl text-xs font-semibold text-slate-200 bg-[#111C38] hover:text-white hover:bg-[#162447] transition shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="fill-current h-4 w-4 ms-1.5 opacity-60" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-400 text-xs hover:bg-[#111C38] font-medium">
                                ចាកចេញពីប្រព័ន្ធ
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</nav>
