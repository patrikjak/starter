<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($metadata)
        <x-pjstarter::metadata.title :title="$metadata->title" />
        <x-pjstarter::metadata.description :description="$metadata->description" />
        <x-pjstarter::metadata.keywords :keywords="$metadata->keywords" />
        <x-pjstarter::metadata.canonical-url :canonical-url="$metadata->canonical_url" />
    @endisset

    @stack('meta')
    @stack('fonts')
    @stack('favicons')
    @stack('styles')
    @stack('scripts')

    @isset($metadata)
        <x-pjstarter::metadata.structured-data :structured-data="$metadata->structured_data" />
    @endisset
</head>