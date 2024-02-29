Projeto desenvolvido por Jorge Grabovschi e Eduardo Palma Ramos Duarte Gonçalves na UC de Tecnologias de Internet.

Para o professor que ler isto:
	1. Não conseguimos gravar vídeo (mas era opcional na mesma, então não deve haver problema);
	2. Quando for testar o código vai ter de meter o seu URL do site nos ficheiros, para os GET's e POST's funcionarem corretamente (se testar o arduino, terá também de alterar o SSID e a PASSWORD que estão no início do ficheiro projeto.ino):
		- Código da página web:
			- Linha 125 do "dashboard.php";
			- Linha 125 do "divisao.php";
			- Linha 143 do "gestao.php";
			- Linha 101 do "historico.php";
			- Linha 106 do "historicoSensor.php";
		- Código do Arduino (projeto.ino):
			- Linha 14;
		- Código do Raspberry Pi (proj.py):
			- Linha 21;
			- Linha 23 (url da câmara usada com o auxilio do DroidCam);
		- Código do Packet Tracer (projeto.pkt):
			- Linha 3 em todos os MCU's (url da API);
	3. É isso. De resto tenha um bom dia :9
