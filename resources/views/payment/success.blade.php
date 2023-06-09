@extends('layouts.app')

@section('content')
    <div class="container-fluid max-w-3xl pt-3">
        @if ((string) $order->state === 'completed')
            <div>
                <div class="d-flex justify-content-center mb-3">
                    <img class="img-fluid" style="max-width: 45%" src="{{ asset('images/illustrations/completed.svg') }}" alt="pago completado">
                </div>
                <p class="text-center">Pago completado, gracias por su compra.</p>
            </div>
        @elseif ((string) $order->state === 'canceled')
            <div>
                <div class="d-flex justify-content-center mb-3">
                    <img class="img-fluid" style="max-width: 45%" src="{{ asset('images/illustrations/canceled.svg') }}" alt="pago cancelado">
                </div>
                <p class="text-center">Pago cancelado, reintente la compra nuevamente.</p>
            </div>
        @elseif ((string) $order->state === 'pending')
            <div>
                <div class="d-flex justify-content-center mb-3">
                    <img class="img-fluid" style="max-width: 45%" src="{{ asset('images/illustrations/pending.svg') }}" alt="pago pendiente">
                </div>
                <p class="text-center">El pago ha quedado pendiente de confirmación.</p>
                <p class="text-center">Le notificaremos cuando sea completado</p>
            </div>
        @endif
        <a class="d-block text-center link-primary" href="/">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M15 6l-6 6l6 6"></path>
            </svg>
            {{ __('Continue shopping') }}
        </a>
    </div>
@endsection
