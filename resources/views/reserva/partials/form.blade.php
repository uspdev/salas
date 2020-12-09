Nome:  <input type="text" name="nome" value="{{  old('nome', $reserva->nome) }}">
<br><br>
Data de início:  <input type="text" name="data_inicio" value="{{  old('data_inicio', $reserva->data_inicio) }}">
<br><br>
Data de fim:  <input type="text" name="data_fim" value="{{  old('data_fim', $reserva->data_fim) }}">
<br><br>
Cor:  <input type="text" name="cor" value="{{  old('cor', $reserva->cor) }}">
<br><br>
<button type="submit"> Enviar </button>