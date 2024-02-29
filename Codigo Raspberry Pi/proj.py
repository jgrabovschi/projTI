import RPi.GPIO as GPIO
import time
import requests
import cv2
import sys
import signal


#pinos das coisas
led = 2
flameSens = 3
luz = 5
GPIO.setmode(GPIO.BCM)

#setup dos pinos
GPIO.setup(led, GPIO.OUT, initial = 0)
GPIO.setup(luz, GPIO.OUT, initial = 0)
GPIO.setup(flameSens, GPIO.IN)

#url do site
url = 'http://10.79.12.144/test'
#url da webcam
webcam_url = "http://10.79.12.145:4747/video"

def keyboard_interrupt_handler(signal, frame):
	print("PROGRAMA INTERROMPIDO")
	#liberta os pinos gpio
	GPIO.cleanup()
	#destroi as janelas utilizadas para captura de imagem
	cv2.destroyAllWindows()
	#sai do programa
	sys.exit(0)

# e executado quando é detetado um sinal para interromper o programa(SIGINT)  
signal.signal(signal.SIGINT, keyboard_interrupt_handler)

while True:
	try:
		#faz get do estado da luz (evento da dashboard para sbc)
		rluz = requests.get(url + '/api/api.php?nome=luz&divisao=sala')
		if rluz.status_code == requests.codes.ok:
			if str(rluz.text) == "Ligada":#liga ou desliga a luz dependendo do estado
				GPIO.output(luz,1)
			else:
				GPIO.output(luz,0)	
		else:
			r.raise_for_status()
		
		#faz get da temperatura do quarto(evento mcu para sbc)
		r = requests.get(url + '/api/api.php?nome=temperatura&divisao=quarto')
		if r.status_code == requests.codes.ok:
			print("Temp: ", r.text)
			if float(r.text) < 28: #liga um LED no caso da temperatura ser menor que 28 graus
				GPIO.output(led,1)
				q = requests.post(url + "/api/api.php", data = {'nome':'led', 'divisao':'quarto', 'hora':str(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime())), 'valor':'Ligada'})
			else:
				GPIO.output(led,0)
				q = requests.post(url + "/api/api.php", data = {'nome':'led', 'divisao':'quarto', 'hora':str(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime())), 'valor':'Desligada'})
			print("luz: ",q)	
		else:
			r.raise_for_status()

		statIncendio = GPIO.input(flameSens) #le se ha presenca de chamas (sensor de chamas)
		if statIncendio: #faz post à api do seu estado
			s = requests.post(url + "/api/api.php", data = {'nome':'chamas', 'divisao':'sala', 'hora':str(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime())), 'valor':'Presentes'})
		else:
			s = requests.post(url + "/api/api.php", data = {'nome':'chamas', 'divisao':'sala', 'hora':str(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime())), 'valor':'Nao Presentes'})
		print("Flame: ", s)
		
		#cria um objeto para captura de imagem
		cap = cv2.VideoCapture(webcam_url)
		ret, frame = cap.read() #captura frame
		if ret: #cria um ficheiro de imagem com o frame (se for devolvido)
			cv2.imwrite('captura.jpg', frame)
		imagem = {'imagem': open('captura.jpg', 'rb')}
		#faz post da imagem e da hora em que foi tirada a foto
		r = requests.post(url + '/api/api.php', files=imagem, data={'hora':str(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime()))})
		print(r.text)
		#liberta o objeto
		cap.release()

	except Exception as e:
		#tratamento de excecoes
		print('ERRO: ', e)
	finally:
		#delay 5 segundos
		time.sleep(5)
