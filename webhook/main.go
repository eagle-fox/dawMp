package main

import (
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

	cmd := exec.Command("bash", "-c", "env && pwd && cd /go/src/app/dawMp && git pull --force origin dev-docker"+gitUsername+":"+gitToken)
	output, err := cmd.CombinedOutput()
	log.Println("Ejecutando git pull... Comando:", cmd.String())
	if err != nil {
		log.Println("Error al ejecutar git pull:", err)
		return
	}

	log.Println("git pull completado exitosamente. Salida:", string(output))
}

func main() {
	log.SetOutput(os.Stdout)
	http.HandleFunc("/push", pushHandler)
	log.Fatal(http.ListenAndServe(":2002", nil))
}
