<p>Guten Tag,</p>

<p>
wir schlagen Ihnen folgenden Wartungstermin vor:
</p>

<p>
<strong>
{{ $proposal->proposed_starts_at->format('d.m.Y H:i') }}
</strong>
</p>

<p>
<a href="{{ $acceptUrl }}">✅ Termin bestätigen</a>
</p>

<p>
<a href="{{ $rejectUrl }}">❌ Termin passt nicht</a>
</p>

<p>
Viele Grüße<br>
Ihr Service-Team
</p>
