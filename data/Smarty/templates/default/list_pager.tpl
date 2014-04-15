
<script>
function fnNaviPage1(pageno) {
    var fm = document.getElementById('form1');
    fm.pageno.value = pageno;
    fm.submit();
}
</script>

  <ul class="pure-u-1-2">
  <!--{if $arrPagenavi.before != $arrPagenavi.now_page}-->
    <li class="prev"><a href="#" onclick="fnNaviPage1('<!--{$arrPagenavi.before}-->');return false;">
    <<前へ</a></li>
  <!--{/if}-->
  <!--{assign var=fst value="1"}-->
  <!--{foreach item=pageno from=$arrPagenavi.arrPageno}-->
    <!--{assign var=fst value=""}-->
    <li>
      <!--{if $pageno == $arrPagenavi.now_page}-->
        <!--{if $pageno < $arrPagenavi.next}--><!--{*真ん中のページの場合*}-->
          <span style="border-right:1px solid #ccc; padding:2px 6px;"><!--{$pageno}--></span>
        <!--{else}--><!--{$pageno}--><!--{/if}-->
      <!--{else}-->
      <a href="#" onclick="fnNaviPage1('<!--{$pageno}-->');return false;"><!--{$pageno}--></a>
      <!--{/if}-->
    </li>
  <!--{/foreach}-->
  <!--{if $arrPagenavi.next != $arrPagenavi.now_page}-->
    <li class="next"><a href="#" onclick="fnNaviPage1('<!--{$arrPagenavi.next}-->');return false;">
    次へ>></a></li>
  <!--{/if}-->
  </ul>
