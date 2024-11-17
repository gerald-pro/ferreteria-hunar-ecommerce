@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-light focus:border-dark dark:bg-slate-800 dark:text-slate-200']) !!}>
