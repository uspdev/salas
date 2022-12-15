<table>
    @foreach($recursos as $recurso)
        <tr>
            <td><input data-id="{{ $recurso->id }}" type="checkbox" class="recurso" name="recursos[]" value="{{ $recurso->id }}"></td>
            <td>{{ $recurso->nome }}</td>
        </tr>
    @endforeach
</table>

