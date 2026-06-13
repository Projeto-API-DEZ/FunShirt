<x-layouts::main-content title="Checkout" heading="Checkout" subheading="Confirm your order details before submitting">
    <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Billing and delivery details</h2>
                <p class="mt-1 text-sm text-zinc-500">These fields are required to create the order.</p>

                @if ($errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST" class="mt-6 space-y-5">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="nif" class="mb-2 block text-sm font-medium text-zinc-700">NIF</label>
                            <input
                                id="nif"
                                name="nif"
                                type="text"
                                value="{{ old('nif', $customer?->nif) }}"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                placeholder="123456789"
                                required
                            >
                        </div>

                        <div>
                            <label for="payment_type" class="mb-2 block text-sm font-medium text-zinc-700">Payment method</label>
                            <select
                                id="payment_type"
                                name="payment_type"
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                required
                            >
                                @php($selectedPaymentType = old('payment_type', $customer?->default_payment_type))
                                <option value="">Select a payment method</option>
                                <option value="Visa" @selected($selectedPaymentType === 'Visa')>Visa</option>
                                <option value="PayPal" @selected($selectedPaymentType === 'PayPal')>PayPal</option>
                                <option value="MB WAY" @selected($selectedPaymentType === 'MB WAY')>MB WAY</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="mb-2 block text-sm font-medium text-zinc-700">Address</label>
                        <textarea
                            id="address"
                            name="address"
                            rows="4"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Delivery address"
                            required
                        >{{ old('address', $customer?->address) }}</textarea>
                    </div>

                    <div>
                        <label for="payment_ref" id="payment-ref-label" class="mb-2 block text-sm font-medium text-zinc-700">Payment reference</label>
                        <input
                            id="payment_ref"
                            name="payment_ref"
                            type="text"
                            value="{{ old('payment_ref', $customer?->default_payment_ref) }}"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Card number, email or phone"
                            required
                        >
                        <p id="payment-ref-help" class="mt-2 text-xs text-zinc-500">
                            Visa: 16 digits starting with 4. PayPal: valid email. MB WAY: 9 digits starting with 9.
                        </p>
                    </div>

                    <div>
                        <label for="notes" class="mb-2 block text-sm font-medium text-zinc-700">Notes</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            placeholder="Optional order notes"
                        >{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-500">
                            Submit order
                        </button>
                        <a href="{{ route('cart.show') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                            Back to cart
                        </a>
                    </div>
                </form>
            </div>

            <aside class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-zinc-900">Order summary</h2>
                <div class="mt-4 space-y-4">
                    @php($total = 0)
                    @foreach ($cart as $item)
                        @php($total += $item['sub_total'])
                        <div class="flex items-start justify-between gap-4 border-b border-zinc-100 pb-4 last:border-b-0 last:pb-0">
                            <div>
                                <p class="text-sm font-medium text-zinc-900">{{ $item['name'] }}</p>
                                <p class="mt-1 text-xs text-zinc-500">
                                    {{ $item['size'] }} · #{{ $item['color_code'] }} · Qty {{ $item['qty'] }}
                                </p>
                            </div>
                            <p class="text-sm font-semibold text-zinc-900">&euro;{{ number_format($item['sub_total'], 2) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-zinc-200 pt-4">
                    <span class="text-sm font-medium text-zinc-600">Total</span>
                    <span class="text-xl font-bold text-zinc-900">&euro;{{ number_format($total, 2) }}</span>
                </div>
            </aside>
        </div>
    </div>

    <script>
        (() => {
            const initPaymentReferenceUI = () => {
                const paymentType = document.getElementById('payment_type');
                const paymentRef = document.getElementById('payment_ref');
                const paymentRefLabel = document.getElementById('payment-ref-label');
                const paymentRefHelp = document.getElementById('payment-ref-help');

                if (!paymentType || !paymentRef || !paymentRefLabel || !paymentRefHelp) {
                    return;
                }

                const syncReferenceField = () => {
                    switch (paymentType.value) {
                        case 'Visa':
                            paymentRefLabel.textContent = 'Card number';
                            paymentRef.placeholder = '4123456789012345';
                            paymentRef.setAttribute('inputmode', 'numeric');
                            paymentRefHelp.textContent = 'Visa references must have 16 digits and start with 4.';
                            break;
                        case 'PayPal':
                            paymentRefLabel.textContent = 'PayPal email';
                            paymentRef.placeholder = 'customer@example.com';
                            paymentRef.setAttribute('inputmode', 'email');
                            paymentRefHelp.textContent = 'PayPal references must be a valid email address.';
                            break;
                        case 'MB WAY':
                            paymentRefLabel.textContent = 'MB WAY phone number';
                            paymentRef.placeholder = '912345678';
                            paymentRef.setAttribute('inputmode', 'numeric');
                            paymentRefHelp.textContent = 'MB WAY references must have 9 digits and start with 9.';
                            break;
                        default:
                            paymentRefLabel.textContent = 'Payment reference';
                            paymentRef.placeholder = 'Card number, email or phone';
                            paymentRef.setAttribute('inputmode', 'text');
                            paymentRefHelp.textContent = 'Visa: 16 digits starting with 4. PayPal: valid email. MB WAY: 9 digits starting with 9.';
                            break;
                    }
                };

                if (!paymentType.dataset.referenceBound) {
                    paymentType.addEventListener('change', syncReferenceField);
                    paymentType.dataset.referenceBound = 'true';
                }

                syncReferenceField();
            };

            document.addEventListener('DOMContentLoaded', initPaymentReferenceUI);
            document.addEventListener('livewire:navigated', initPaymentReferenceUI);
            initPaymentReferenceUI();
        })();
    </script>
</x-layouts::main-content>
