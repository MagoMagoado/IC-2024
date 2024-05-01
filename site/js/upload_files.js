document.addEventListener("DOMContentLoaded", function () {
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
	App.init = (function () {
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

			//confirm and save
			$("#confirm-upload-files").addEventListener("click", evt => {
				evt.preventDefault();

				var ajax = new XMLHttpRequest();
				var formdata = new FormData();

				ajax.onreadystatechange = function () {
					if (ajax.status === 200) {
						if (ajax.readyState === 4) {
							console.log('OK! Enviado solicitação');
						}
					}
					else {
						console.error('Error 404 Page Not Found!');
					}
				}

				Object.keys(files).forEach(fileIndex => {
					let file = files[fileIndex];
					let fileType = file.type; // Obtém o tipo do arquivo

					// Verifica se o tipo de arquivo é CSV
					if (fileType === 'text/csv' || file.name.endsWith('.csv')) {
						formdata.append('files[]', file);
					} else{
						ajax.abort();
						console.error('Arquivo não é do tipo csv:', file.name);
						$("#mensagens-alerta").innerHTML = `
						<div class="alert alert--info">
							<p> <strong>Warning!</strong> Please select a csv file.</p>
						</div>
						`;
					}
				});

				ajax.open('POST', 'http://localhost/IC-2024/site/php/saveFile.php');
				ajax.send(formdata);

				ajax.onload = function(){
					//transforma em JSON
					let respostaAjax = null;
					try {
						respostaAjax = JSON.parse(ajax.responseText);
					} catch (e) {
						$("#mensagens-alerta").innerHTML = `
						<div class="alert alert--error">
							<p> <strong>Error!</strong> Files couldn't be sent.</p>
						</div>
						`;
						console.error('Não conseguiu converter em JSON');
					};
					if (respostaAjax) {
						if (respostaAjax.BD === 0) {
							if (respostaAjax.response === 1) {
								$("#mensagens-alerta").innerHTML = `
								<div class="alert alert--success">
									<p> <strong>Success!</strong> files were sent successfully.</p>
								</div>
								`;
								console.log(respostaAjax.message);
	
								////Muda o botão de cancelar////
								////Muda o botão de cancelar////
							$("#confirm-upload-files").innerHTML = `return`;
							$("#confirm-upload-files").addEventListener("click", evt => {
								$("#mensagens-alerta").innerHTML = ``;
								$(".lines-update").innerHTML = ``;
								evt.preventDefault();
								$("#files-active").classList.add("hidden");
								$("#drop").classList.remove("hidden");
							});
								/////////////////////////////////
							} else{
								$("#mensagens-alerta").innerHTML = `
								<div class="alert alert--error">
									<p> <strong>Error!</strong> Files couldn't be sent.</p>
								</div>
								`;
								console.error(respostaAjax.message);
							}
						}
						else {
							$("#mensagens-alerta").innerHTML = `
								<div class="alert alert--warning">
									<p> <strong>Warning!</strong> Database already exists.</p>
								</div>
								`;
							$(".lines-update").innerHTML = `
								<div class="warningBD">
									<svg class="icon-warning" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
										<path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
									</svg>
									<p>Warning!</p>
									Database already exists.<br>
									Do you want to continue uploading or delete existing files?
								</div>
							`;
							$(".buttons-upload").classList.add("btnBDexist");
							$("#confirm-upload-files").addEventListener("click", evt => {
								evt.preventDefault();
								formdata.append(1, BDjaCriado);
								console.log(formdata);
							});

						}
					}
				}
				//fim ajax.onload

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
			location.reload();
		});


		// input change
		$("input[type=file]").addEventListener("change", handleFileSelect);

	})();
});