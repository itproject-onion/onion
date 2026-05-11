<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex 
    items-center justify-center px-8 py-4 bg-[#D2A679] 
    border border-transparent rounded-full font-black text-white uppercase tracking-widest hover:bg-[#C49A74] focus:bg-[#C49A74] active:bg-[#A67B5B] focus:outline-none focus:ring-2 focus:ring-[#D6B18D] focus:ring-offset-2 transition ease-in-out duration-150 w-full']) }}>
    {{ $slot }}
</button>