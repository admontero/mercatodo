<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Best Selling Product') }}</title>
    <style>
        .bg-gray-200 {
            background-color: rgb(229 231 235);
        }

        .border-b {
            border-bottom-width: 1px;
        }

        .border-gray-200 {
            border-color: rgb(229 231 235);
        }

        .capitalize {
            text-transform: capitalize;
        }

        .leading-normal {
            line-height: 1.5;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .table-auto {
            table-layout: auto;
        }

        .text-gray-600 {
            color: rgb(75 85 99);
        }

        .text-center {
            text-align: center;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .w-full {
            width: 100%;
        }
    </style>
</head>
<body>
    <h1 class="text-center">{{ __('Best Selling Product') }}</h1>
    <p class="text-sm">
        <strong>{{ __('Date') }}:</strong>
        <span class="capitalize">{{ now()->isoFormat('MMMM D YYYY hh:mm:ss a') }}</span>
    </p>

    <table class="w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">
                <th class="py-1 px-6">#</th>
                <th class="py-1 px-6">{{ __('Code') }}</th>
                <th class="py-1 px-6">{{ __('Name') }}</th>
                <th class="py-1 px-6">{{ __('Orders Completed') }}</th>
                <th class="py-1 px-6">{{ __('Units Purchased') }}</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm">
            @foreach ($data as $product)
                <tr class="border-b border-gray-200">
                    <td class="py-1 px-6"><strong>{{ $loop->iteration }}</strong></td>
                    <td class="py-1 px-6">{{ $product['code'] }}</td>
                    <td class="py-1 px-6">{{ $product['name'] }}</td>
                    <td class="py-1 px-6 text-center">{{ $product['orders_completed'] }}</td>
                    <td class="py-1 px-6 text-center">{{ $product['units_purchased'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
