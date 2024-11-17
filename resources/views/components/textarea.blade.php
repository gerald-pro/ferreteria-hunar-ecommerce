@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-light focus:border-dark block w-full dark:bg-slate-800 dark:text-slate-200']) !!}>{{ $slot }}</textarea>