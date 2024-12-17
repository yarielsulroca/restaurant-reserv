@extends('errors.minimal')
@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Demasiadas solicitudes, por favor espera un momento.'))