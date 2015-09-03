<div id="footer-wrap" {if $editing == true}class="editcontent"{/if}>
    <div id="footer">
        <div id="powered-by"><a href="http://mahara.org/"><img src="{theme_url filename='images/powered_by_mahara.png'}" border="0" alt="Powered by Mahara"></a></div>

        <!-- there is a div id="performance-info" wrapping this -->{mahara_performance_info}
        <div id="version">{mahara_version}</div>
        <div class="cb"></div>
    </div><!-- footer -->
</div><!-- footer-wrap -->

</body>
</html>