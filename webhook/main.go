package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	"os/exec"
)

func pushHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, "Método no permitido", http.StatusMethodNotAllowed)
		return
	}

	err := r.ParseForm()
	if err != nil {
		http.Error(w, "Error al analizar el formulario", http.StatusBadRequest)
		return
	}

	gitUsername := os.Getenv("GIT_USERNAME")
	gitToken := os.Getenv("GIT_TOKEN")

	if gitUsername == "" || gitToken == "" {
		http.Error(w, "Error al obtener las variables de entorno", http.StatusInternalServerError)
		return
	}

	cmdString := fmt.Sprintf("cd /go/src/app/dawMp && git pull --force https://%s:%s@github.com/%s/dawMp.git dev-docker", gitUsername, gitToken, gitUsername)
	cmd := exec.Command("bash", "-c", cmdString)
	output, err := cmd.CombinedOutput()
	log.Println("Ejecutando git pull... Comando:", cmd.String())
	if err != nil {
		log.Println("Error al ejecutar git pull:", err)
		return
	}

	log.Println("git pull completado exitosamente. Salida:", string(output))
	log.Println("Intentando reconstruir la imagen de docker...")

	cmdString = fmt.Sprintf("cd /go/src/app/dawMp && docker-compose up -d --build --force-recreate")
	cmd = exec.Command("bash", "-c", cmdString)
	output, err = cmd.CombinedOutput()
	log.Println("Ejecutando docker-compose up... Comando:", cmd.String())
	if err != nil {
		log.Println("Error al ejecutar docker-compose up:", err)
		return
	}

	log.Println("docker-compose up completado exitosamente. Salida:", string(output))

}

func main() {
	log.SetOutput(os.Stdout)
	http.HandleFunc("/push", pushHandler)
	log.Fatal(http.ListenAndServe(":2002", nil))
}
