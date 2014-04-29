jQuery(function($){
	function loadSearch() {
		$search = $("#search-live");
		$.get($search.attr("action"), $search.serialize(), function(data) {
			$("#load-list").replaceWith(data);
			$("#list-table").tablesorter();
		});
	}

	$("body").on("click", "#list .entry *", function() {
		window.location = "details?id=" + $(this).parents(".entry").attr("data-id");
	});

	$("body").on("click", "#buckets .entry *", function() {
		window.location = base + "buckets/edit?id=" + $(this).parents(".entry").attr("data-id");
	});

	$("body").on("keyup", "#query-live", function() {
		loadSearch();
	});

	$("body").on("change", "#category-live", function() {
		loadSearch();
	});

	$("body").on("submit", "#search-live", function(e) {
		e.preventDefault();

		loadSearch();
	});

	$("body").on("keyup", "#savename", function() {
		var text = " " + $(this).val();

		if ($(this).val() == "") {
			text = "&hellip;";
		}

		$(".entry-name").html(text);
	});

	$("body").on("click", "#edit .entry *", function() {
		var $checkbox = $(this).parents(".entry").find(".row-toggle");
		
		var checked = $checkbox.is(":checked");

		if (checked) {
			$checkbox.removeProp("checked", false);
		} else {
			$checkbox.prop("checked", true);
		}

		$.post(base + "toggle-relation", {
			currentId: $checkbox.attr("data-current-id"),
			relatedId: $checkbox.attr("name"),
			value: checked
		});
	});

	$(".data").tablesorter();
});