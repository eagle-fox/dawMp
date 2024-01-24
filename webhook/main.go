package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
	"os/exec"
)

func pushHandler(w http.ResponseWriter, r *http.Request) {
	// secret := r.Header.Get("X-Hub-Signature")
	// if secret != "sha1="+secretKey {
	// 	http.Error(w, "No autorizado", http.StatusUnauthorized)
	// 	return
	// }

	if r.Method != http.MethodPost {
		http.Error(w, "Método no permitido", http.StatusMethodNotAllowed)
		return
	}

	err := r.ParseForm()
	if err != nil {
		http.Error(w, "Error al analizar el formulario", http.StatusBadRequest)
		return
	}

	// Obtener credenciales de autenticación para el repositorio privado
	gitUsername := os.Getenv("GIT_USERNAME")
	gitToken := os.Getenv("GIT_TOKEN")

	// Verificar que las credenciales estén definidas
	if gitUsername == "" || gitToken == "" {
		log.Println("Error: Debes definir GIT_USERNAME y GIT_TOKEN para autenticar en el repositorio privado.")
		http.Error(w, "Error de autenticación", http.StatusInternalServerError)
		return
	}

	// Configurar las credenciales para el git pull
	gitCredentials := fmt.Sprintf("%s:%s@", gitUsername, gitToken)

	// Ejecutar el git pull en el repositorio privado
	cmd := exec.Command("sh", "-c", fmt.Sprintf("cd /ruta/a/tu/repositorio && git pull https://%sgithub.com/usuario/repo.git", gitCredentials))
	output, err := cmd.CombinedOutput()
	if err != nil {
		log.Println("Error al hacer git pull:", err)
		http.Error(w, "Error al hacer git pull", http.StatusInternalServerError)
		return
	} else {
		log.Println("Git pull completado exitosamente. Salida:", string(output))
	}

}

func main() {
	log.SetOutput(os.Stdout)

	http.HandleFunc("/push", pushHandler)
	log.Fatal(http.ListenAndServe(":2002", nil))
}
