var pieces = [];

$.getJSON( "data/pieces.json")
	.done(function( data ) {
		$.each( data.pieces, function( key, piece ) {
			pieces.push(piece);

		});
	});


$.getJSON( "data/packs.json")
	.done(function( data ) {
		$.each( data.packs, function( key, pack ) {
			var packDetails = "";
			var packPieces = "";
			$.each(pack.pieces, function(k, piece){
				var result = $.grep(pieces, function(e){ return e.id == piece; });
				if(result.length > 0) packPieces += "<li>"+result[0].name+"</li>";
			});
			$("<li/>",{
				html:pack.id+" - "+pack.name+"<ul>"+packPieces+"</ul>"
			}).appendTo("#packs");
	});
});