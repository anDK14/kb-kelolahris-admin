<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data FAQ</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; }
        h2 { text-align: center; margin-bottom: 10px; }
        .answer { font-size: 11px; }
        .answer ul, .answer ol { margin: 0; padding-left: 15px; }
        .answer li { margin-bottom: 2px; }
        .answer strong, .answer b { font-weight: bold; }
        .answer em, .answer i { font-style: italic; }
        .answer u { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>Data FAQ</h2>
    <table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>
                @if($feature_type === 'website')
                    Nama Website Feature
                @elseif($feature_type === 'mobile')
                    Nama Mobile Feature
                @else
                    Nama Website Feature / Nama Mobile Feature
                @endif
            </th>
            <th>Pertanyaan</th>
            <th>Jawaban</th>
            <th>Dibuat Pada</th>
            <th>Diupdate Pada</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($faqs as $faq)
            <tr>
                <td>
                    @if($feature_type === 'website')
                        {{ $faq->submodule->name ?? '-' }}
                    @elseif($feature_type === 'mobile')
                        {{ $faq->mobileFeature->name ?? '-' }}
                    @else
                        {{ $faq->submodule->name ?? $faq->mobileFeature->name ?? '-' }}
                    @endif
                </td>
                <td>{{ $faq->question }}</td>
                <td class="answer">{!! $faq->answer !!}</td>
                <td>{{ $faq->created_at?->format('d/m/Y H:i') }}</td>
                <td>{{ $faq->updated_at?->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data FAQ</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>