@component('mail::message')
    # Notificação de veículo com pendencia

    Há um veículo com pendencia:

    - **ID:** {{ $vehicle->id() }}
    - **Fabricante:** {{ is_null($vehicle->manufacturer()) ? 'N/A' : $vehicle->manufacturer()->value() }}
    - **Cor:** {{ is_null($vehicle->color()) ? 'N/A' : $vehicle->color()->value() }}
    - **Modelo:** {{ is_null($vehicle->model()) ? 'N/A' : $vehicle->model()->value() }}
    - **Placa:** {{ $vehicle->licensePlate()->value() }}
    - **Data de Entrada:** {{ is_null($vehicle->entryTimes()) ? 'N/A' : $vehicle->entryTimes()->value()->format('d-m-Y H:i:s') }}
    - **Data de Saída:** {{ is_null($vehicle->departureTimes()) ? 'N/A' : $vehicle->departureTimes()->value()->format('d-m-Y H:i:s')}}
    - **Pendências:**
    @foreach ($vehicle->pending() as $pending)
        - Tipo: {{ $pending->type()->value() }}
        - Descrição: {{ is_null($pending->description()) ? 'N/A' : $pending->description()->value()  }}
    @endforeach

    Obrigado por utilizar nossa aplicação!

    Atenciosamente,<br>
    {{ config('app.name') }}
@endcomponent
