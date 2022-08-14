<!--<div id="{{ $date_field . '_container' }}" class="six columns date_container">-->
  <div style="margin-top: 17px">
    <p>   
      <label>Século:&nbsp;</label>
      <select id="century{{ $date_field }}" name="century{{ $date_field }}" class="date" >
        <option value="NS">Escolha o século</option>
        <option value="Before">Antes do século XV</option>
        <option value="XV">Século XV</option>
        <option value="XVI">Século XVI</option>
        <option value="XVII">Século XVII</option>
        <option value="XVIII">Século XVIII</option>
        <option value="XIX">Século XIX</option>
        <option value="XX">Século XX</option>
        <option value="XXI">Século XXI</option>
      </select>
           
      <span class="space_period" id="period_select{{ $date_field }}" name="period_select{{ $date_field }}"></span>
      <br>
      <br>
      <label>Década:</label>
      <select id="decade_select{{ $date_field }}" name="decade_select{{ $date_field }}" placeholder="Decada">
            <option value="">Escolha a década</option>
            <option value="BD">Anterior ao ano de 1401</option>
            <option value="1401 a 1410">1401 a 1410</option>
            <option value="1411 a 1420">1411 a 1420</option>
            <option value="1421 a 1430">1421 a 1430</option>
            <option value="1431 a 1440">1431 a 1440</option>
            <option value="1441 a 1450">1441 a 1450</option>
            <option value="1451 a 1460">1451 a 1460</option>
            <option value="1461 a 1470">1461 a 1470</option>
<option value="1471 a 1480">1471 a 1480</option>
<option value="1481 a 1490">1481 a 1490</option>
<option value="1491 a 1500">1491 a 1500</option>
<option value="1501 a 1510">1501 a 1510</option>
<option value="1511 a 1520">1511 a 1520</option>
<option value="1521 a 1530">1521 a 1530</option>
<option value="1531 a 1540">1531 a 1540</option>
<option value="1541 a 1550">1541 a 1550</option>
<option value="1551 a 1560">1551 a 1560</option>
<option value="1561 a 1570">1561 a 1570</option>
<option value="1571 a 1580">1571 a 1580</option>
<option value="1581 a 1590">1581 a 1590</option>
<option value="1591 a 1600">1591 a 1600</option>
<option value="1601 a 1610">1601 a 1610</option>
<option value="1611 a 1620">1611 a 1620</option>
<option value="1621 a 1630">1621 a 1630</option>
<option value="1631 a 1640">1631 a 1640</option>
<option value="1641 a 1650">1641 a 1650</option>
<option value="1651 a 1660">1651 a 1660</option>
<option value="1661 a 1670">1661 a 1670</option>
<option value="1671 a 1680">1671 a 1680</option>
<option value="1681 a 1690">1681 a 1690</option>
<option value="1691 a 1700">1691 a 1700</option> 
<option value="1701 a 1710">1701 a 1710</option>
<option value="1711 a 1720">1711 a 1720</option>
<option value="1721 a 1730">1721 a 1730</option>
<option value="1731 a 1740">1731 a 1740</option>
<option value="1741 a 1750">1741 a 1750</option>
<option value="1751 a 1760">1751 a 1760</option>
<option value="1761 a 1770">1761 a 1770</option>
<option value="1771 a 1780">1771 a 1780</option>
<option value="1781 a 1790">1781 a 1790</option>
<option value="1791 a 1800">1791 a 1800</option>
<option value="1801 a 1810">1801 a 1810</option>
<option value="1811 a 1820">1811 a 1820</option>
<option value="1821 a 1830">1821 a 1830</option>
<option value="1831 a 1840">1831 a 1840</option>
<option value="1841 a 1850">1841 a 1850</option>
<option value="1851 a 1860">1851 a 1860</option>
<option value="1861 a 1870">1861 a 1870</option>
<option value="1871 a 1880">1871 a 1880</option>
<option value="1881 a 1890">1881 a 1890</option>
<option value="1891 a 1900">1891 a 1900</option>
<option value="1901 a 1910">1901 a 1910</option>
<option value="1911 a 1920">1911 a 1920</option>
<option value="1921 a 1930">1921 a 1930</option>
<option value="1931 a 1940">1931 a 1940</option>
<option value="1941 a 1950">1941 a 1950</option>
<option value="1951 a 1960">1951 a 1960</option>
<option value="1961 a 1970">1961 a 1970</option>
<option value="1971 a 1980">1971 a 1980</option>
<option value="1981 a 1990">1981 a 1990</option>
<option value="1991 a 2000">1991 a 2000</option>
<option value="2001 a 2010">2001 a 2010</option>
<option value="2011 a 2020">2011 a 2020</option>
      </select>      
        <br>
<div class="separate_text">   
@if($date_field != "_image")     
<a onclick="close_other_date('otherDate');" class="btn btn_data_ok" >OK</a>
@else
<a onclick="close_other_date('date_img_inaccurate');" class="btn btn_data_ok" >OK</a>
@endif
</div>

    </p>
  </div>
<!--</div>-->