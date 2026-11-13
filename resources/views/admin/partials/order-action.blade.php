<form method="POST" action="{{ route('admin.orders.status', $order) }}" class="inline"
      data-confirm="{{ $action['confirm'] }}" data-confirm-title="{{ $action['label'] }} pesanan #{{ $order->id }}?"
      data-confirm-button="{{ $action['label'] }}" @if ($action['status'] === 'rejected') data-confirm-danger @endif>
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="{{ $action['status'] }}">
    <button type="submit" class="rounded-lg font-semibold transition {{ $class }} {{ $action['style'] }}">{{ $action['label'] }}</button>
</form>
