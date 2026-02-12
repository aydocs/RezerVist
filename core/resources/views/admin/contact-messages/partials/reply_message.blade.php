<div class="flex items-start gap-4 flex-row-reverse animate-fade-in-up">
    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center shrink-0 border-4 border-white shadow-md">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    </div>
    <div class="flex-1 max-w-[85%]">
        <div class="flex items-center gap-2 mb-1 ml-auto justify-end">
            <span class="text-[10px] font-black text-indigo-600 uppercase tracking-wider">Siz (Admin)</span>
            <span class="text-[10px] text-gray-400">&bull; {{ $date->diffForHumans() }}</span>
        </div>
        <div class="bg-gray-900 text-white px-6 py-4 rounded-[24px] rounded-tr-none shadow-xl shadow-gray-900/10 text-sm font-medium leading-relaxed">
            {!! nl2br(e($reply)) !!}
        </div>
    </div>
</div>
