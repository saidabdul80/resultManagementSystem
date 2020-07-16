//JQuery Paging Plugin v1.4
//Written By Adnan ŞAHİN

(function($) {
  "use strict";

  $.fn.JPaging = function(param) {
    var params = $.extend({ pageSize: 7, visiblePageSize: 1 }, param);

    var $pageSize = params.pageSize;

    var $visiblePageSize = params.visiblePageSize;

    var $thisId = $(this).attr("id");
    $("<div class='pagingL'></div>").insertAfter(this);

    var $countLi = $(this).find("li").length;

    var $currentIndex = 2;
    var $pageCount = Math.round($countLi / $pageSize);
    if ($countLi > 0) {
      if ($pageSize * $pageCount < $countLi) {
        $pageCount++;
      }
    }
    if ($visiblePageSize == 0) {
      $visiblePageSize = 1;
    }

    //sayfa linkleri
    if ($pageCount >= 1 && $visiblePageSize >= 1) {
      $(".pagingL").append(
        "<a href='javascript:void(0)' style='font-weight:700;'>" + "Prev"
      );
      /*
      if ($pageCount > $visiblePageSize) {
        $(".pagingL").append(
          "<a href='javascript:void(0)' id='pre_point' class='hidden'>" + ".."
        );
      }

      for (var i = 1; i <= $pageCount; i++) {
        if (i <= $visiblePageSize) {
          $(".pagingL").append("<a href='javascript:void(0)'>" + i + "</a>");
        } else if (i > $visiblePageSize) {
          $(".pagingL").append(
            "<a href='javascript:void(0)' class='hidden'>" + i + "</a>"
          );
        }
      }
      if ($pageCount > $visiblePageSize) {
        $(".pagingL").append(
          "<a href='javascript:void(0)' id='next_point'>" + ".."
        );
      }
      */
      $(".pagingL").append(
        "<a href='javascript:void(0)' style='font-weight:700;'>" + "Next"
      );

      $("ul#" + $thisId + " li:gt(" + ($pageSize - 1) + ")").hide();
      $(".pagingL a:eq(" + $currentIndex + ")").addClass("aktif");
    }
    $("#pre_point").on("click", function(event) {
      event.preventDefault();
      var prevIndex = $(this)
        .nextAll("a:not('.hidden,#next_point')")
        .first()
        .index();
      var hideIndex = prevIndex + $visiblePageSize - 1;
      $(".pagingL a:eq(" + hideIndex + ")").addClass("hidden");
      $(".pagingL a").removeClass("aktif");
      $(".pagingL a:eq(" + (prevIndex - 1) + ")")
        .removeClass("hidden")
        .addClass("aktif");
      $currentIndex = prevIndex - 1;
      var gt = $pageSize * ($currentIndex - 1);
      $("ul#" + $thisId + " li").hide();
      for (var i = gt - $pageSize; i < gt; i++) {
        $("ul#" + $thisId + " li:eq(" + i + ")").show();
      }
      if ($currentIndex - 1 == $pageCount && $visiblePageSize < $pageCount) {
        $("#next_point").addClass("hidden");
      } else if (
        $currentIndex < $pageCount + $visiblePageSize &&
        $visiblePageSize < $pageCount
      ) {
        $("#next_point").removeClass("hidden");
      }
      if ($currentIndex > 2 && $visiblePageSize < $pageCount) {
        $("#pre_point").removeClass("hidden");
      } else if ($currentIndex <= 2 && $visiblePageSize < $pageCount) {
        $("#pre_point").addClass("hidden");
      }
    });
    $("#next_point").on("click", function(event) {
      event.preventDefault();
      var prevIndex = $(this)
        .prevAll("a:not('.hidden')")
        .first()
        .index();
      console.log("prevIndex:" + prevIndex);
      var hideIndex = prevIndex - $visiblePageSize + 1;
      $(".pagingL a:eq(" + hideIndex + ")").addClass("hidden");
      $(".pagingL a").removeClass("aktif");
      $(".pagingL a:eq(" + (prevIndex + 1) + ")")
        .removeClass("hidden")
        .addClass("aktif");
      $currentIndex = prevIndex;
      var gt = $pageSize * $currentIndex;
      $("ul#" + $thisId + " li").hide();
      for (var i = gt - $pageSize; i < gt; i++) {
        $("ul#" + $thisId + " li:eq(" + i + ")").show();
      }
      if ($currentIndex == $pageCount && $visiblePageSize < $pageCount) {
        $("#next_point").addClass("hidden");
      } else if ($currentIndex < $pageCount && $visiblePageSize < $pageCount) {
        $("#next_point").removeClass("hidden");
      }
      if ($currentIndex > $visiblePageSize && $visiblePageSize < $pageCount) {
        $("#pre_point").removeClass("hidden");
      } else if (
        $currentIndex < $visiblePageSize &&
        $visiblePageSize < $pageCount
      ) {
        $("#pre_point").addClass("hidden");
      }
    });
    $(".pagingL").on("click", "a:not('#pre_point,#next_point')", function() {
      var $index = $(this).index();
      console.log(
        "curindex:" +
          $currentIndex +
          " visible_page_count:" +
          $visiblePageSize +
          " pageCount:" +
          $pageCount
      );
      if ($(this).is(".pagingL a:first") === true) {
        if ($currentIndex === 2) {
          return false;
        }
        if ($currentIndex - 2 == $pageCount && $visiblePageSize < $pageCount) {
          $("#next_point").addClass("hidden");
        } else if (
          $currentIndex - 2 <= $pageCount - $visiblePageSize &&
          $visiblePageSize < $pageCount
        ) {
          $("#next_point").removeClass("hidden");
        }
        if ($currentIndex - 1 > 2 && $visiblePageSize < $pageCount) {
          $("#pre_point").removeClass("hidden");
        } else if ($currentIndex - 1 <= 2 && $visiblePageSize < $pageCount) {
          $("#pre_point").addClass("hidden");
        }
        $currentIndex = $currentIndex - 1;
        var gtFirst = $pageSize * ($currentIndex - 1);
        $(".pagingL a").removeClass("aktif");
        $(".pagingL a:not('#next_point'):eq(" + $currentIndex + ")").addClass(
          "aktif"
        );
        $(".pagingL a:not('#next_point'):eq(" + $currentIndex + ")").removeClass(
          "hidden"
        );
        if ($(".pagingL a.hidden").length >= 1) {
          $(
            ".pagingL a:not('#next_point,.pagingL a:last'):eq(" +
              ($currentIndex + $visiblePageSize) +
              ")"
          ).addClass("hidden");
        }
        $("ul#" + $thisId + " li").hide();
        for (var f = gtFirst - $pageSize; f < gtFirst; f++) {
          $("ul#" + $thisId + " li:eq(" + f + ")").show();
        }
        console.log(
          "end curindex:" +
            $currentIndex +
            " visible_page_count:" +
            $visiblePageSize +
            " pageCount:" +
            $pageCount
        );
        return false;
      }
      if ($(this).is(".pagingL a:last") === true) {
        if ($currentIndex - 1 === $pageCount) {
          return false;
        }
        if ($currentIndex == $pageCount && $visiblePageSize < $pageCount) {
          $("#next_point").addClass("hidden");
        } else if (
          $currentIndex < $pageCount &&
          $visiblePageSize < $pageCount
        ) {
          $("#next_point").removeClass("hidden");
        }
        if ($currentIndex > $visiblePageSize && $visiblePageSize < $pageCount) {
          $("#pre_point").removeClass("hidden");
        } else if (
          $currentIndex < $visiblePageSize &&
          $visiblePageSize < $pageCount
        ) {
          $("#pre_point").addClass("hidden");
        }
        $currentIndex = $currentIndex + 1;
        var gtLast = $pageSize * ($currentIndex - 1);
        $(".pagingL a").removeClass("aktif");
        $(".pagingL a:eq(" + $currentIndex + ")").addClass("aktif");
        $(".pagingL a:eq(" + $currentIndex + ")").removeClass("hidden");
        if (
          $currentIndex - 1 > $visiblePageSize &&
          $(".pagingL a.hidden").length >= 1
        ) {
          console.log("cc" + ($currentIndex - $visiblePageSize));
          $(
            ".pagingL a:not('#next_point,.pagingL a:last'):eq(" +
              ($currentIndex - $visiblePageSize) +
              ")"
          ).addClass("hidden");
        }
        $("ul#" + $thisId + " li").hide();
        for (var k = gtLast - $pageSize; k < gtLast; k++) {
          $("ul#" + $thisId + " li:eq(" + k + ")").show();
        }
        return false;
      }
      $currentIndex = $index - 1;
      var gt = $pageSize * $currentIndex;
      $(".pagingL a").removeClass("aktif");
      $(this).addClass("aktif");
      $("ul#" + $thisId + " li").hide();
      for (var i = gt - $pageSize; i < gt; i++) {
        $("ul#" + $thisId + " li:eq(" + i + ")").show();
      }
    });
  };
})(jQuery);
