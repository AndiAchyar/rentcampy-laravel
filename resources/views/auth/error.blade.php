@if ($errors->any())
    <div role="alert" class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <svg aria-hidden="true" viewBox="0 0 20 20" fill="currentColor" class="mt-0.5 h-5 w-5 shrink-0">
            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-1-11a1 1 0 1 1 2 0v3a1 1 0 1 1-2 0V7Zm1 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
@endif
