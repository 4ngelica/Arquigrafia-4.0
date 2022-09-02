<div class="three columns alpha omega">
  <p>
    @if ($drafts->getCurrentPage() <= 1)
      <a id="less" href="#" class="disabled less-than" onclick="return false;"> &lt; </a>
    @else
      <a id="less" href="#" class="less-than"> &lt; </a>
    @endif
    &nbsp;
    {{ Form::text('page', $drafts->getCurrentPage(),
      array('style' => 'width: 30px;', 'class' => 'page_number')) }}
    / <span class="draft_last_page">{{ $drafts->getLastPage() }}</span>
    &nbsp;
    @if ($drafts->getCurrentPage() >= $drafts->getLastPage())
      <a id="greater" href="#" class="disabled greater-than" onclick="return false;"> &gt; </a>
    @else
      <a id="greater" href="#" class="greater-than"> &gt; </a>
    @endif
  </p>
</div>
<table class="form-table drafts" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>Tombo</th>
      <th>Suporte</th>
      <th>Título</th>
      <th><i class="upload"></i></th>
      <th><span class="delete_image_button"></span></th>
    </tr>
  </thead>
  <tbody>
    @include('draft_list')
  </tbody>
</table>
<div class="three columns alpha omega">
  <p>
    @if ($drafts->getCurrentPage() <= 1)
      <a id="less" href="#" class="disabled less-than" onclick="return false;"> &lt; </a>
    @else
      <a id="less" href="#" class="less-than"> &lt; </a>
    @endif
    &nbsp;
    {{ Form::text('page', $drafts->getCurrentPage(),
      array('style' => 'width: 30px;', 'class' => 'page_number')) }}
    / <span class="draft_last_page">{{ $drafts->getLastPage() }}</span>
    &nbsp;
    @if ($drafts->getCurrentPage() >= $drafts->getLastPage())
      <a id="greater" href="#" class="disabled greater-than" onclick="return false;"> &gt; </a>
    @else
      <a id="greater" href="#" class="greater-than"> &gt; </a>
    @endif
  </p>
</div>
<script type="text/javascript">
  var paginator = {
    current_page: {{ $drafts->getCurrentPage() }},
    last_page: {{ $drafts->getLastPage() }},
    number_items: {{ $drafts->count() }},
    per_page: {{ $drafts->getPerPage() }},
    url: "{{ URL::to('/drafts/paginate/') }}"
  };
  var delete_url = "{{ URL::to('/drafts/delete') }}";
</script>
<script src="{{ URL::to('/js/drafts.js') }}"></script>