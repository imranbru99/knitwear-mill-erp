<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $screen }} floor</title>
    <link rel="stylesheet" href="{{ asset('css/mill.css') }}">
    <link rel="manifest" href="/floor.webmanifest">
</head>
<body class="floor">
<main>
    <p class="kicker">ফ্লোর · {{ $screen }}</p>
    <h1>{{ ucfirst($screen) }} screen</h1>
    @if(session('status'))<p class="status">{{ session('status') }}</p>@endif
    <form method="post">
        @csrf
        <label>বারকোড / Barcode <input name="barcode" autofocus inputmode="text"></label>
        @if($screen === 'knit')
            <label>মেশিন
                <select name="machine_id">
                    <option value="">—</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}">{{ $machine->code }} {{ $machine->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>ডাউনটাইম কারণ <input name="reason" placeholder="needle break, yarn break, no yarn"></label>
        @endif
        <label>নোট <input name="note"></label>
        <label><input type="checkbox" name="raise_issue" value="1"> ইস্যু খুলুন</label>
        <button>সেভ</button>
    </form>
    <p><a href="{{ route('home') }}">Site</a></p>
</main>
<script>
document.querySelector('form').addEventListener('submit', function (event) {
    if (navigator.onLine) return;
    event.preventDefault();
    const queue = JSON.parse(localStorage.getItem('floor-queue') || '[]');
    queue.push({screen: @json($screen), body: new FormData(event.target)});
    const plain = {};
    new FormData(event.target).forEach((value, key) => plain[key] = value);
    const saved = JSON.parse(localStorage.getItem('floor-plain') || '[]');
    saved.push({screen: @json($screen), fields: plain, at: Date.now()});
    localStorage.setItem('floor-plain', JSON.stringify(saved));
    alert('অফলাইন। লাইন ফিরলে আবার পাঠান।');
});
</script>
</body>
</html>
