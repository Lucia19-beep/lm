console.log("combate.js cargado");

document.getElementById("boton-combatir").addEventListener("click", () => {

    // Tabla de efectividades: tipo atacante -> tipo defensor -> multiplicador
    const efectividades = {
        Normal: { Normal: 1, Fire: 1, Water: 1, Electric: 1, Grass: 1, Ice: 1, Fighting: 1, Poison: 1, Ground: 1, Flying: 1, Psychic: 1, Bug: 1, Rock: 0.5, Ghost: 0, Dragon: 1, Dark: 1, Steel: 0.5, Fairy: 1 },
        Fire: { Normal: 1, Fire: 0.5, Water: 0.5, Electric: 1, Grass: 2, Ice: 2, Fighting: 1, Poison: 1, Ground: 1, Flying: 1, Psychic: 1, Bug: 2, Rock: 0.5, Ghost: 1, Dragon: 0.5, Dark: 1, Steel: 2, Fairy: 1 },
        Water: { Normal: 1, Fire: 2, Water: 0.5, Electric: 1, Grass: 0.5, Ice: 1, Fighting: 1, Poison: 1, Ground: 2, Flying: 1, Psychic: 1, Bug: 1, Rock: 2, Ghost: 1, Dragon: 0.5, Dark: 1, Steel: 1, Fairy: 1 },
        Electric: { Normal: 1, Fire: 1, Water: 2, Electric: 0.5, Grass: 0.5, Ice: 1, Fighting: 1, Poison: 1, Ground: 0, Flying: 2, Psychic: 1, Bug: 1, Rock: 1, Ghost: 1, Dragon: 0.5, Dark: 1, Steel: 1, Fairy: 1 },
        Grass: { Normal: 1, Fire: 0.5, Water: 2, Electric: 1, Grass: 0.5, Ice: 1, Fighting: 1, Poison: 0.5, Ground: 2, Flying: 0.5, Psychic: 1, Bug: 0.5, Rock: 2, Ghost: 1, Dragon: 0.5, Dark: 1, Steel: 0.5, Fairy: 1 },
        Ice: { Normal: 1, Fire: 0.5, Water: 0.5, Electric: 1, Grass: 2, Ice: 0.5, Fighting: 1, Poison: 1, Ground: 2, Flying: 2, Psychic: 1, Bug: 1, Rock: 1, Ghost: 1, Dragon: 2, Dark: 1, Steel: 0.5, Fairy: 1 },
        Fighting: { Normal: 2, Fire: 1, Water: 1, Electric: 1, Grass: 1, Ice: 2, Fighting: 1, Poison: 0.5, Ground: 1, Flying: 0.5, Psychic: 0.5, Bug: 0.5, Rock: 2, Ghost: 0, Dragon: 1, Dark: 2, Steel: 2, Fairy: 0.5 },
        Poison: { Normal: 1, Fire: 1, Water: 1, Electric: 1, Grass: 2, Ice: 1, Fighting: 1, Poison: 0.5, Ground: 0.5, Flying: 1, Psychic: 1, Bug: 1, Rock: 0.5, Ghost: 0.5, Dragon: 1, Dark: 1, Steel: 0, Fairy: 2 },
        Ground: { Normal: 1, Fire: 2, Water: 1, Electric: 2, Grass: 0.5, Ice: 1, Fighting: 1, Poison: 2, Ground: 1, Flying: 0, Psychic: 1, Bug: 0.5, Rock: 2, Ghost: 1, Dragon: 1, Dark: 1, Steel: 2, Fairy: 1 },
        Flying: { Normal: 1, Fire: 1, Water: 1, Electric: 0.5, Grass: 2, Ice: 1, Fighting: 2, Poison: 1, Ground: 1, Flying: 1, Psychic: 1, Bug: 2, Rock: 0.5, Ghost: 1, Dragon: 1, Dark: 1, Steel: 0.5, Fairy: 1 },
        Psychic: { Normal: 1, Fire: 1, Water: 1, Electric: 1, Grass: 1, Ice: 1, Fighting: 2, Poison: 2, Ground: 1, Flying: 1, Psychic: 0.5, Bug: 1, Rock: 1, Ghost: 1, Dragon: 1, Dark: 0, Steel: 0.5, Fairy: 1 },
        Bug: { Normal: 1, Fire: 0.5, Water: 1, Electric: 1, Grass: 2, Ice: 1, Fighting: 0.5, Poison: 0.5, Ground: 1, Flying: 0.5, Psychic: 2, Bug: 1, Rock: 1, Ghost: 0.5, Dragon: 1, Dark: 2, Steel: 0.5, Fairy: 0.5 },
        Rock: { Normal: 1, Fire: 2, Water: 1, Electric: 1, Grass: 1, Ice: 2, Fighting: 0.5, Poison: 1, Ground: 0.5, Flying: 2, Psychic: 1, Bug: 2, Rock: 1, Ghost: 1, Dragon: 1, Dark: 1, Steel: 0.5, Fairy: 1 },
        Ghost: { Normal: 0, Fire: 1, Water: 1, Electric: 1, Grass: 1, Ice: 1, Fighting: 1, Poison: 1, Ground: 1, Flying: 1, Psychic: 2, Bug: 1, Rock: 1, Ghost: 2, Dragon: 1, Dark: 0.5, Steel: 1, Fairy: 1 },
        Dragon: { Normal: 1, Fire: 1, Water: 1, Electric: 1, Grass: 1, Ice: 1, Fighting: 1, Poison: 1, Ground: 1, Flying: 1, Psychic: 1, Bug: 1, Rock: 1, Ghost: 1, Dragon: 2, Dark: 1, Steel: 0.5, Fairy: 0 },
        Dark: { Normal: 1, Fire: 1, Water: 1, Electric: 1, Grass: 1, Ice: 1, Fighting: 0.5, Poison: 1, Ground: 1, Flying: 1, Psychic: 2, Bug: 1, Rock: 1, Ghost: 2, Dragon: 1, Dark: 0.5, Steel: 1, Fairy: 0.5 },
        Steel: { Normal: 1, Fire: 0.5, Water: 0.5, Electric: 0.5, Grass: 1, Ice: 2, Fighting: 1, Poison: 1, Ground: 1, Flying: 1, Psychic: 1, Bug: 1, Rock: 2, Ghost: 1, Dragon: 1, Dark: 1, Steel: 0.5, Fairy: 2 },
        Fairy: { Normal: 1, Fire: 0.5, Water: 1, Electric: 1, Grass: 1, Ice: 1, Fighting: 2, Poison: 0.5, Ground: 1, Flying: 1, Psychic: 1, Bug: 1, Rock: 1, Ghost: 1, Dragon: 2, Dark: 2, Steel: 0.5, Fairy: 1 }
    };

    
    /*function capitalizarTipo(tipo) {
        return tipo.trim().charAt(0).toUpperCase() + tipo.trim().slice(1).toLowerCase();
    }*/

    function getMultiplicador(tipoAtacante, tiposDefensor) {
        let multi = 1;
        const tipoAtk = tipoAtacante;
        for (let i = 0; i < tiposDefensor.length; i++) {
            const tipoDef = tiposDefensor[i];
            const mod = efectividades[tipoAtk] ? efectividades[tipoAtk][tipoDef] : undefined;
            console.log(`Atacante: ${tipoAtk}, Defensor: ${tipoDef}, Modificador: ${mod}`);
            if (mod !== undefined) {
                multi *= mod;
            }
        }
        return multi;
    }


    function mostrarPokemonVisual(jugador, rival) {
        const divJugador = document.getElementById("jugador");
        const divRival = document.getElementById("rival");

        divJugador.innerHTML = "";
        divRival.innerHTML = "";

        for (let i = 0; i < 6; i++) {
            const poke1 = jugador[i];
            const poke2 = rival[i];

            const img1 = document.createElement("img");
            img1.src = "img/img/sprites/gifs/" + poke1.Name + ".gif";
            img1.alt = poke1.Name;
            img1.title = poke1.Name;
            img1.className = "pokemon-img";
            img1.onerror = function () {
                this.onerror = null;
                this.src = "img/img/sprites/pngs/" + poke1.Name + ".png";
            };

            const img2 = document.createElement("img");
            img2.src = "img/img/sprites/gifs/" + poke2.Name + ".gif";
            img2.alt = poke2.Name;
            img2.title = poke2.Name;
            img2.className = "pokemon-img";
            img2.onerror = function () {
                this.onerror = null;
                this.src = "img/img/sprites/pngs/" + poke2.Name + ".png";
            };

            divJugador.appendChild(img1);
            divRival.appendChild(img2);
        }
    }

   function simularCombate(jugador, rival) {
        const log = [];

        function danoBase() {
            return Math.floor(Math.random() * (20 - 10 + 1)) + 10; // base 10 a 20
        }

        let indiceJugador = 0;
        let indiceRival = 0;

        while (indiceJugador < 6 && indiceRival < 6) {
            const poke1 = jugador[indiceJugador];
            const poke2 = rival[indiceRival];
            console.log("Tipos del jugador:", poke1.Type);
            console.log("Tipos del rival:", poke2.Type);
            log.push("🟦 " + poke1.Name + " (HP: " + poke1.HP + ") entra al combate vs 🟥 " + poke2.Name + " (HP: " + poke2.HP + ")");
            
            

            while (poke1.HP > 0 && poke2.HP > 0) {
                // Construimos arrays de tipos para ambos Pokémon
                const tiposJugador = [];
                if (poke1.Type && poke1.Type.length > 0) {
                    for (let i = 0; i < poke1.Type.length; i++) {
                        if (poke1.Type[i] !== "") tiposJugador.push(poke1.Type[i]);
                    }
                }

                const tiposRival = [];
                if (poke2.Type && poke2.Type.length > 0) {
                    for (let i = 0; i < poke2.Type.length; i++) {
                        if (poke2.Type[i] !== "") tiposRival.push(poke2.Type[i]);
                    }
                }

                if (poke1.Speed >= poke2.Speed) {
                    // Ataque jugador -> rival
                    const tipoAtaque = tiposJugador[Math.floor(Math.random() * tiposJugador.length)];
                    const multi = getMultiplicador(tipoAtaque, tiposRival);

                    let dano1 = Math.floor(danoBase() * multi);
                    poke2.HP -= dano1;
                    if (poke2.HP < 0) poke2.HP = 0;
                    log.push(poke1.Name + " ataca (x" + multi.toFixed(2) + "). " + poke2.Name + " pierde " + dano1 + " HP.");

                    if (poke2.HP <= 0) {
                        log.push(poke2.Name + " ha sido derrotado.");
                        break;
                    }

                    // Contraataque rival -> jugador
                    const tipoAtaqueRival = tiposRival[Math.floor(Math.random() * tiposRival.length)];
                    const multiRival = getMultiplicador(tipoAtaqueRival, tiposJugador);

                    let dano2 = Math.floor(danoBase() * multiRival);
                    poke1.HP -= dano2;
                    if (poke1.HP < 0) poke1.HP = 0;
                    log.push(poke2.Name + " contraataca (x" + multiRival.toFixed(2) + "). " + poke1.Name + " pierde " + dano2 + " HP.");

                    if (poke1.HP <= 0) {
                        log.push(poke1.Name + " ha sido derrotado.");
                        break;
                    }
                } else {
                    // Ataque rival -> jugador
                    const tipoAtaque = tiposRival[Math.floor(Math.random() * tiposRival.length)];
                    const multi = getMultiplicador(tipoAtaque, tiposJugador);

                    let dano1 = Math.floor(danoBase() * multi);
                    poke1.HP -= dano1;
                    if (poke1.HP < 0) poke1.HP = 0;
                    log.push(poke2.Name + " ataca (x" + multi.toFixed(2) + "). " + poke1.Name + " pierde " + dano1 + " HP.");

                    if (poke1.HP <= 0) {
                        log.push(poke1.Name + " ha sido derrotado.");
                        break;
                    }

                    // Contraataque jugador -> rival
                    const tipoContraataque = tiposJugador[Math.floor(Math.random() * tiposJugador.length)];
                    const multiContraataque = getMultiplicador(tipoContraataque, tiposRival);

                    let dano2 = Math.floor(danoBase() * multiContraataque);
                    poke2.HP -= dano2;
                    if (poke2.HP < 0) poke2.HP = 0;
                    log.push(poke1.Name + " contraataca (x" + multiContraataque.toFixed(2) + "). " + poke2.Name + " pierde " + dano2 + " HP.");

                    if (poke2.HP <= 0) {
                        log.push(poke2.Name + " ha sido derrotado.");
                        break;
                    }
                }
            }


            log.push("------");

            if (poke1.HP <= 0) indiceJugador++;
            if (poke2.HP <= 0) indiceRival++;
        }

        if (indiceJugador >= 6 && indiceRival >= 6) {
            log.push("¡Empate!");
        } else if (indiceJugador >= 6) {
            log.push("¡Has perdido :(!");
        } else {
            log.push("¡Has ganado :)!");
             log.push("¡Has ganado 2 sobres como recompensa :D !");
            fetch("actualizar_sobres.php")
            .then(r => r.text())
            .then(txt => console.log("Recompensa aplicada:", txt));
        }

        console.log(" RESULTADO DEL COMBATE:");
        console.log(log.join("\n"));

        const divResultado = document.getElementById("resultadoCombate");
        if (divResultado) {
            divResultado.innerHTML = " RESULTADO DEL COMBATE:<br><br>" + log.join("<br>");
        } else {
            console.warn("No se encontro el div #resultadoCombate");
        }
    }

    fetch("combate.php")
        .then(res => res.text())
        .then(txt => {
            console.log("Respuesta bruta:", txt);

            let data;
            try {
                data = JSON.parse(txt);
            } catch (e) {
                console.error("Error al parsear JSON:", e);
                document.body.innerHTML = "<pre>" + txt + "</pre>";
                return;
            }

            if (data.error) {
                alert("Error: " + data.error);
                return;
            }

           
            //Como los tipos se llaman Type 1 y Type 2, con un espacio entre medias, hay que convertirlos a un array"
            for (let i = 0; i < data.jugador.length; i++) {
                data.jugador[i].HP = parseFloat(data.jugador[i].HP);
                data.jugador[i].Type = [data.jugador[i]['Type 1']]; 
            }
            for (let i = 0; i < data.rival.length; i++) {
                data.rival[i].HP = parseFloat(data.rival[i].HP);
                data.rival[i].Type = [data.rival[i]['Type 1']];
            }

            console.log("Jugador:", data.jugador);
            console.log("Rival:", data.rival);

            mostrarPokemonVisual(data.jugador, data.rival);
            simularCombate(data.jugador, data.rival);
        })
        .catch(error => {
            console.error("Error al iniciar el combate:", error);
            alert("Hubo un error al contactar con el servidor.");
        });

});