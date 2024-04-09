document.addEventListener("DOMContentLoaded", function() {
    const $ = document.querySelector.bind(document);
	const loadFiles = `
		<div class="carregandoFiles">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4335 4335">
						<path fill="#4db6ac" d="M3346 1077c41,0 75,34 75,75 0,41 -34,75 -75,75 -41,0 -75,-34 -75,-75 0,-41 34,-75 75,-75zm-1198 -824c193,0 349,156 349,349 0,193 -156,349 -349,349 -193,0 -349,-156 -349,-349 0,-193 156,-349 349,-349zm-1116 546c151,0 274,123 274,274 0,151 -123,274 -274,274 -151,0 -274,-123 -274,-274 0,-151 123,-274 274,-274zm-500 1189c134,0 243,109 243,243 0,134 -109,243 -243,243 -134,0 -243,-109 -243,-243 0,-134 109,-243 243,-243zm500 1223c121,0 218,98 218,218 0,121 -98,218 -218,218 -121,0 -218,-98 -218,-218 0,-121 98,-218 218,-218zm1116 434c110,0 200,89 200,200 0,110 -89,200 -200,200 -110,0 -200,-89 -200,-200 0,-110 89,-200 200,-200zm1145 -434c81,0 147,66 147,147 0,81 -66,147 -147,147 -81,0 -147,-66 -147,-147 0,-81 66,-147 147,-147zm459 -1098c65,0 119,53 119,119 0,65 -53,119 -119,119 -65,0 -119,-53 -119,-119 0,-65 53,-119 119,-119z"
						/>
					</svg>
		</div>
		`;

    let App = {};
    App.init = (function() {
        //Init
        function handleFileSelect(evt) {
            const files = evt.target.files; // FileList object

			
			$(".lines-update").innerHTML = loadFiles;

			$("#drop").classList.add("hidden");
			$("#files-active").classList.remove("hidden");

			
			let linesFiles = `${Object.keys(files)
				.map(file =>
					`<div class="linha">
						<p class="name">${files[file].name}</p>
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000">
							<g><path id="path" d="M500,10C229.4,10,10,229.4,10,500c0,270.6,219.4,490,490,490c270.6,0,490-219.4,490-490C990,229.4,770.6,10,500,10z M500,967.7C241.7,967.7,32.3,758.3,32.3,500C32.3,241.7,241.7,32.3,500,32.3c258.3,0,467.7,209.4,467.7,467.7C967.7,758.3,758.3,967.7,500,967.7z M748.4,325L448,623.1L301.6,477.9c-4.4-4.3-11.4-4.3-15.8,0c-4.4,4.3-4.4,11.3,0,15.6l151.2,150c0.5,1.3,1.4,2.6,2.5,3.7c4.4,4.3,11.4,4.3,15.8,0l308.9-306.5c4.4-4.3,4.4-11.3,0-15.6C759.8,320.7,752.7,320.7,748.4,325z"</g>
						</svg>
					</div>
					`)
				.join("")}`;

			
			Object.keys(files).forEach(file => {
				let load = 2000 + (file * 2000); // fake load
				setTimeout(() => {
					$(".lines-update").innerHTML = `${linesFiles}`;
				}, load);
			});
        }

		

		//trigger
        $("#triggerFile").addEventListener("click", evt => {
            evt.preventDefault();
            $("input[type=file]").click();
        });
		// drop events
		$("#drop").ondragleave = evt => {
			$("#drop").classList.remove("active");
			evt.preventDefault();
		};
		$("#drop").ondragover = $("#drop").ondragenter = evt => {
			$("#drop").classList.add("active");
			evt.preventDefault();
		};
		$("#drop").ondrop = evt => {
			$("input[type=file]").files = evt.dataTransfer.files;
			$("#drop").classList.remove("active");
			handleFileSelect();
			evt.preventDefault();
		};
		//cancel
		$("#cancel-upload-files").addEventListener("click", evt => {
			$(".lines-update").innerHTML = ``;
            evt.preventDefault();
			$("#files-active").classList.add("hidden");
			$("#drop").classList.remove("hidden");
        });


		// input change
		$("input[type=file]").addEventListener("change", handleFileSelect);

    })();
});