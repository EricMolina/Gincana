<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        td {
            border: 1px solid black;
            padding: 5px 15px;
        }
        
        #app {
            width: 1500px;
        }

        #app > div {
            width: 45%;
            height: fit-content;
            padding: 10px;
            border: 1px solid black;
            float: left;
        }

        .new-point {
            margin: 10px 0px;
        }
        
        #map { 
            height: 500px;
            width: 650px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>

    @if(!Session::get('current_activity'))    
        
    <div id="app">
        <div id="content">

        </div>
        <div id="form">

        </div>
    </div>

    <script>window.onload = () => displayGincanas();</script>

    @else

    <div id="app">
        <div id="content">

        </div>
        <div id="form">
            <div id="map"></div><br>
            <span id="check-result-msg"></span>
        </div>
    </div>

    <script>
        window.onload = () => {
            displayCurrentActivityStatus();

            var map = L.map('map').setView([41.35005355319907, 2.1073615730168473], 14);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var marker = new L.marker([41.35005355319907, 2.1073615730168473], {id:1, draggable:'true'});
            marker.on('dragend', function(event){
                    var marker = event.target;
                    var position = marker.getLatLng();
                    marker.setLatLng(position,{id:1,draggable:'true'}).bindPopup(position).update();
                    sendCheckpoint(position);
            });
            map.addLayer(marker);

        }
    </script>

    @endif


</body>
<script>

    var appContent = document.getElementById('content');
    var appForm = document.getElementById('form');
    var currentGincana;
    var currentSession;



    /* GINCANA FUNCTIONALITIES */

    var gincanaPointsCount = 1;


    async function getPoints() {
        let points = "";

        await fetch('{{ route("api.points.list") }}')
        .then((res) => res.text())
        .then((text) => {
            points = JSON.parse(text);
        })

        return points;
    }


    function displayGincanas() {
        fetch('{{ route("api.gincanas.list") }}')
        .then((res) => res.text())
        .then((text) => {
            let gincanas = JSON.parse(text);
            let content = "";
            let rows = "";

            gincanas.forEach(gincana => {
                rows += `
                    <tr>
                        <td>${gincana.name}</td>
                        <td>${gincana.coord_x}, ${gincana.coord_y}</td>
                        <td>${gincana.gincana_points_count}</td>
                        <td><button onclick="displaySessions(${gincana.id})">Sessions</button></td>
                        <td>${gincana.is_owner ? '<button>x</button>' : ''}</td>
                    </tr>
                `;
            });
            
            content += `
                <button id="create-gincana-btn">Create gincana</button>
                <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Points</th>
                        <th>Sessions</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
                </table>
            `;          
            
            appContent.innerHTML = content;
            document.getElementById('create-gincana-btn').onclick = () => displayGincanaForm();
        })
    }


    function displayGincanaForm() {
        appForm.innerHTML = `
            <input type="text" id="gincana-name" placeholder="name" />
            <input type="text" id="gincana-desc" placeholder="description" />
            <input type="number" id="gincana-difficulty" placeholder="difficulty" />
            <input type="number" id="gincana-coord_x" placeholder="coord_x" />
            <input type="number" id="gincana-coord_y" placeholder="coord_y" /><br><br>

            <button id="submit-gincana-btn">Create</button>
            <button id="add-point-btn">Add point</button><br><br>

            <div id="gincana-points-container"></div>
        `;

        document.getElementById('add-point-btn').onclick = () => addGincanaPoint();
        document.getElementById('submit-gincana-btn').onclick = () => createGincana();
    }


    function addGincanaPoint() {
        let points;
        let currentGincanaPoint = gincanaPointsCount;

        document.getElementById('gincana-points-container').innerHTML += `
            <div class="new-point">
                <select id="gincana-points-point-${currentGincanaPoint}"></select><br>
                <label>Hint</label><br>
                <textarea id="gincana-points-hint-${currentGincanaPoint}"></textarea>
            </div>
        `;
        
        getPoints().then((points) => {
            let pointsSelect = document.getElementById(`gincana-points-point-${currentGincanaPoint}`);
            
            points.forEach(point => {
                pointsSelect.innerHTML += `<option value="${point.id}">${point.name}</option>`
            });
        });

        gincanaPointsCount++;
    }


    function createGincana() {
        let gincanaPoints = [];

        for (let index = 1; index < 100; index++) {
            let pointId = document.getElementById(`gincana-points-point-${index}`);
            let pointHint = document.getElementById(`gincana-points-hint-${index}`);

            if (!pointId) {
                break;
            }

            gincanaPoints.push({
                'order_id': index,
                'point_id': pointId.value,
                'hint': pointHint.value
            });
        }

        let data = {
            'name': document.getElementById('gincana-name').value,
            'desc': document.getElementById('gincana-desc').value,
            'difficulty': document.getElementById('gincana-difficulty').value,
            'coord_x': document.getElementById('gincana-coord_x').value,
            'coord_y': document.getElementById('gincana-coord_y').value,
            'points': gincanaPoints
        };

        fetch('{{ route("api.gincanas.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })

        appForm.innerHTML = "";
        gincanaPointsCount = 0;
        displayGincanas();
    }



    /* SESSIONS FUNCTIONALITIES */

    function displaySessions(gincanaId) {
        currentGincana = gincanaId;

        fetch(`{{ route("api.sessions.list") }}?id=${gincanaId}`)
        .then((res) => res.text())
        .then((text) => {
            let sessions = JSON.parse(text);
            let content = "";
            let rows = "";

            sessions.forEach(session => {
                rows += `
                    <tr>
                        <td>${session.name}</td>
                        <td>${session.session_code}</td>
                        <td><button onclick="displayGroups(${session.id})">Groups</button></td>
                        <td>${session.is_owner ? '<button>x</button>' : ''}</td>
                    </tr>
                `;
            });
            
            content += `
                <button id="create-session-btn">Create session</button>
                <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Groups</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
                </table>
            `;          
            
            appContent.innerHTML = content;
            document.getElementById('create-session-btn').onclick = () => displaySessionForm();
        })
    }


    function displaySessionForm() {
        appForm.innerHTML = `
            <input type="text" id="session-name" placeholder="name" /><br><br>
            <button id="submit-session-btn">Create</button>
        `;

        document.getElementById('submit-session-btn').onclick = () => createSession();
    }


    function createSession() {
        let data = {
            'name': document.getElementById('session-name').value,
            'gincana_id': currentGincana
        };

        fetch('{{ route("api.sessions.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })

        appForm.innerHTML = "";
        displaySessions(currentGincana);
    }



    /* GROUPS FUNCTIONALITIES */

    function displayGroups(sessionId) {
        currentSession = sessionId;

        fetch(`{{ route("api.groups.list") }}?id=${sessionId}`)
        .then((res) => res.text())
        .then((text) => {
            let groups = JSON.parse(text);
            let content = "";
            let rows = "";

            groups.forEach(group => {
                rows += `
                    <tr>
                        <td>${group.name}</td>
                        <td>${group.status}</td>
                        <td><ul>
                `;

                group.gincana_session_group_users.forEach(user => {
                    rows += `<li>${user.user.name}</li>`;
                });

                rows += `
                        <ul><td>
                        <td><button onclick="joinGroup(${group.id})">Join</button></td>
                    </tr>
                `;
            });
            
            content += `
                <button id="create-group-btn">Create group</button>
                <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Members</th>
                        <th>Join</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
                </table>
            `;          
            
            appContent.innerHTML = content;
            document.getElementById('create-group-btn').onclick = () => displayGroupForm();
        })
    }


    function displayGroupForm() {
        appForm.innerHTML = `
            <input type="text" id="group-name" placeholder="name" /><br><br>
            <button id="submit-group-btn">Create</button>
        `;

        document.getElementById('submit-group-btn').onclick = () => createGroup();
    }

    
    function createGroup() {
        let data = {
            'name': document.getElementById('group-name').value,
            'gincana_session_id': currentSession
        };

        fetch('{{ route("api.groups.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(() => {
            location.reload();
        })

        appForm.innerHTML = "";
        displayGroups(currentSession);
    }


    function joinGroup(groupId) {
        let data = {
            'gincana_session_group_id': groupId
        };

        fetch('{{ route("api.groups.join") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(() => {
            location.reload();
        })
    }



    /* CURRENT ACTIVITY FUNCTIONALITIES */

    function displayCurrentActivityStatus() {
        fetch('{{ route("api.current_activity.status") }}')
        .then((res) => res.text())
        .then((text) => {
            let activity = JSON.parse(text);

            appContent.innerHTML = `
                <h2>${activity.gincana.name}</h2>
                <h3>${activity.session.name} [${activity.session.session_code}] - 
                    ${activity.session.status == 0 ? `Esperando jugadores` : `En juego`}</h3>

                <p>Mi grupo: ${activity.group.name}</p>
                <ul>
            `;

            activity.group.gincana_session_group_users.forEach(member => {
                appContent.innerHTML += `<li>${member.user.name}</li>`;
            });

            appContent.innerHTML += `
                </ul><br>
                ${activity.session.is_owner && activity.session.status != 1 ? 
                    '<button onclick="startGincanaSession()">Iniciar gincana</button><br><br>' : ''}
                <button onclick="exitGroup()">Abandonar grupo</button><br><br>
            `;

            if (activity.session.status == 1) {
                activity.available_points.forEach((point, index) => {
                    appContent.innerHTML += `
                        <div>
                            <b>Punto ${index + 1} (${point.members_in_point}/${activity.group.gincana_session_group_users_count})</b><br>
                            ${point.hint ? `<span>Pista: ${point.hint}</span>` : ''}
                        </div><br>
                    `;
                });
            }

            if (activity.ranking) {
                appContent.innerHTML += `
                    <b>Gincana completada!</b>
                `;

                activity.ranking.forEach(group => {
                    appContent.innerHTML += `<br><b>#${group.group_position} - ${group.group_name}</b>`;
                });

                appContent.innerHTML += '<br><br><button onclick="exitCurrentActivity()">Salir</button>';
            }
        })
    }


    function sendCheckpoint(position) {
        let data = {
            'coord_x': position['lat'],
            'coord_y': position['lng']
        };

        fetch('{{ route("api.current_activity.checkpoint") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then((res) => res.text())
        .then((text) => {
            let response = JSON.parse(text);

            if (response['result'] == 'ok') {
                displayCurrentActivityStatus();
                document.getElementById('check-result-msg').textContent = 'Punto encontrado';

            } else if (response['result' ]== 'wait') {
                document.getElementById('check-result-msg').textContent = 'Espera a que el resto lleguen al punto';

            } else if (response['result'] == 'no')  {
                document.getElementById('check-result-msg').textContent = 'No se ha encontrado el punto';

            }

        })
    }


    function exitGroup() {
        fetch('{{ route("api.groups.exit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            location.reload();
        })
    }


    function startGincanaSession() {
        fetch('{{ route("api.sessions.start") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            displayCurrentActivityStatus();
        })
    }


    function exitCurrentActivity() {
        fetch('{{ route("api.current_activity.exit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            location.reload();
        })
    }

</script>
</html>