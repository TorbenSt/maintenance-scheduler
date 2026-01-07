<p>Guten Tag,</p>

<p>bitte wählen Sie einen passenden Termin aus:</p>

<ul>
@foreach($items as $item)
    <li>
        <strong>{{ $item['proposal']->proposed_starts_at->format('d.m.Y H:i') }}</strong>
        – <a href="{{ $item['acceptUrl'] }}">Bestätigen</a>
        | <a href="{{ $item['rejectUrl'] }}">Passt nicht</a>
    </li>
@endforeach
</ul>

<p>Viele Grüße<br>Ihr Service-Team</p>
