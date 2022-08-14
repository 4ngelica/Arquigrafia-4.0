<div id="{{ $date_field . '_container' }}" class="six columns date_container">
  <div class="six columns">
    <p>   
      <label>Formato:</label>
      <select class="date">
        <option value="day">dia/mês/ano</option>
        <option value="month">mês/ano</option>
        <option value="year">ano</option>
        <option value="decade">década</option>
        <option value="century">século</option>
      </select>
      <br>
      <label>Intervalo:</label>
      <input type="radio" name="{{ $date_field }}_radio" class="date_interval" value="" checked>
      <label>Uma única data</label>
      <input type="radio" name="{{ $date_field }}_radio" class="date_interval" value="interval">
      <label>Intervalo entre duas datas</label>
    </p>
  </div>
  <div class="six columns date_content">
    <div class="date_box">
      <input type="text" name="{{ $date_field }}1" class="day"
        placeholder="Ex.: {{ date('d/m/Y') }}" />
      <p class="date_translation"></p>
    </div>
    <div class="one column" style="width: 15px; text-align: center;"><p class="interval_text"></p></div>
    <div class="date_box"></div>
  </div>
</div>