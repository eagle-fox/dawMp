package main

import (
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

	cmd := exec.Command("sh", "-c", "cd dawMp && git fetch")
	output, err := cmd.CombinedOutput()
	if err != nil {
		log.Println("Error al ejecutar git fetch:", err)
		return
	}

	log.Println("git fetch completado exitosamente. Salida:", string(output))
}

func main() {
	log.SetOutput(os.Stdout)

	http.HandleFunc("/push", pushHandler)
	log.Fatal(http.ListenAndServe(":2002", nil))
}
