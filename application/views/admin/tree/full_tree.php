<div class="col-12 p-0">
  <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden; background: #f8fafc;">
    
    <!-- Top Header & Controls -->
    <div class="card-header bg-white border-0 py-3 px-4">
      <div class="row align-items-center justify-content-between">
        <div class="col-md-5 col-12 mb-2 mb-md-0">
          <h3 class="mb-1 text-dark" style="font-weight: 700;">
            <i class="fas fa-sitemap text-primary mr-2"></i> All Members Full Tree View
          </h3>
          <p class="text-muted text-sm mb-0">सर्व मेंबर्सचे संपूर्ण बायनरी ट्री स्ट्रक्चर एकाच स्क्रीनवर पहा, झूम करा व सर्च करा.</p>
        </div>

        <!-- Search and Quick Actions -->
        <div class="col-md-7 col-12 d-flex flex-wrap align-items-center justify-content-md-end" style="gap: 8px;">
          <div class="input-group input-group-sm" style="max-width: 260px;">
            <div class="input-group-prepend">
              <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
            </div>
            <input type="text" id="treeSearchInput" class="form-control border-left-0" placeholder="Search ID or Name...">
            <div class="input-group-append">
              <button class="btn btn-primary btn-sm" type="button" id="btnTreeSearch">Search</button>
            </div>
          </div>

          <button class="btn btn-outline-primary btn-sm" id="btnZoomIn" title="Zoom In"><i class="fas fa-plus"></i></button>
          <button class="btn btn-outline-primary btn-sm" id="btnZoomOut" title="Zoom Out"><i class="fas fa-minus"></i></button>
          <button class="btn btn-outline-secondary btn-sm" id="btnResetZoom" title="Reset View"><i class="fas fa-sync-alt"></i> Reset</button>
          <button class="btn btn-outline-info btn-sm" id="btnExpandAll" title="Expand All"><i class="fas fa-expand-arrows-alt"></i> Expand All</button>
          <button class="btn btn-outline-warning btn-sm" id="btnCollapseAll" title="Collapse All"><i class="fas fa-compress-arrows-alt"></i> Collapse</button>
          <button class="btn btn-dark btn-sm" id="btnFullscreen" title="Fullscreen"><i class="fas fa-arrows-alt"></i></button>
        </div>
      </div>

      <!-- Stats Bar & Legend -->
      <div class="row mt-3 pt-2 border-top align-items-center">
        <div class="col-md-7 col-12 d-flex flex-wrap align-items-center" style="gap: 15px;">
          <span class="badge badge-pill badge-primary px-3 py-2 font-weight-600">
            Total Members: <b id="statTotal">0</b>
          </span>
          <span class="badge badge-pill badge-success px-3 py-2 font-weight-600">
            Active: <b id="statActive">0</b>
          </span>
          <span class="badge badge-pill badge-danger px-3 py-2 font-weight-600">
            Blocked: <b id="statBlocked">0</b>
          </span>
          <span class="badge badge-pill badge-secondary px-3 py-2 font-weight-600">
            Inactive: <b id="statInactive">0</b>
          </span>
        </div>
        <div class="col-md-5 col-12 d-flex justify-content-md-end align-items-center mt-2 mt-md-0" style="gap: 12px; font-size: 13px;">
          <span><i class="fas fa-circle text-success mr-1"></i> Active</span>
          <span><i class="fas fa-circle text-danger mr-1"></i> Blocked</span>
          <span><i class="fas fa-circle text-secondary mr-1"></i> Inactive</span>
          <span><i class="fas fa-circle text-light border mr-1"></i> Vacant Node</span>
        </div>
      </div>
    </div>

    <!-- Interactive Tree Canvas Container -->
    <div id="treeViewport" style="width: 100%; height: 75vh; min-height: 550px; overflow: hidden; position: relative; background: #eef2f6; cursor: grab; user-select: none;">
      
      <!-- Zoom / Pan Canvas -->
      <div id="treeCanvas" style="transform-origin: 0 0; position: absolute; left: 50%; top: 40px; transition: transform 0.08s ease-out;">
        <div id="treeContainer" class="d-flex justify-content-center"></div>
      </div>

      <!-- Minimap / Helper Tip -->
      <div style="position: absolute; bottom: 12px; left: 16px; background: rgba(255,255,255,0.9); padding: 6px 14px; border-radius: 8px; font-size: 12px; color: #64748b; box-shadow: 0 2px 6px rgba(0,0,0,0.06); pointer-events: none;">
        <i class="fas fa-mouse mr-1"></i> <b>Drag</b> to Pan &bull; <b>Scroll</b> to Zoom &bull; <b>Click Node</b> to View/Actions
      </div>
    </div>

  </div>
</div>

<!-- Member Details Modal -->
<div class="modal fade" id="memberDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title text-white font-weight-bold" id="modalMemberName">Member Details</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-3">
          <div id="modalAvatar" class="avatar avatar-xl rounded-circle mx-auto mb-2 text-white font-weight-bold d-flex align-items-center justify-content-center" style="font-size: 24px;"></div>
          <h4 class="mb-0" id="modalNameText"></h4>
          <span class="badge badge-pill mt-1" id="modalStatusBadge"></span>
        </div>

        <table class="table table-sm table-bordered mt-3 text-sm">
          <tbody>
            <tr><th class="bg-light" style="width: 40%;">Member ID:</th><td id="mId"></td></tr>
            <tr><th class="bg-light">Sponsor ID:</th><td id="mSponsor"></td></tr>
            <tr><th class="bg-light">Position ID:</th><td id="mPosition"></td></tr>
            <tr><th class="bg-light">Placement Leg:</th><td id="mLeg"></td></tr>
            <tr><th class="bg-light">Package:</th><td id="mPackage"></td></tr>
            <tr><th class="bg-light">Rank:</th><td id="mRank"></td></tr>
            <tr><th class="bg-light">Phone:</th><td id="mPhone"></td></tr>
            <tr><th class="bg-light">Join Date:</th><td id="mJoinDate"></td></tr>
            <tr><th class="bg-light">Activation Date:</th><td id="mActDate"></td></tr>
            <tr><th class="bg-light">Left (A) / Right (B):</th><td id="mChildren"></td></tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer bg-light py-2 d-flex justify-content-between">
        <a id="modalLoginLink" href="#" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-sign-in-alt mr-1"></i> Login Portal</a>
        <div>
          <a id="modalEditLink" href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit mr-1"></i> Edit</a>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Styles for Modern Tree Hierarchy -->
<style>
  .tree-node {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    margin: 0 10px;
    position: relative;
  }
  
  .tree-card {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border: 2px solid #e2e8f0;
    width: 170px;
    padding: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    z-index: 2;
  }
  .tree-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    border-color: #5e72e4;
  }
  .tree-card.active-status {
    border-top: 4px solid #2dce89;
  }
  .tree-card.blocked-status {
    border-top: 4px solid #f5365c;
    background: #fff5f5;
  }
  .tree-card.inactive-status {
    border-top: 4px solid #adb5bd;
    background: #fdfdfd;
  }
  .tree-card.highlight-search {
    border-color: #fb6340 !important;
    box-shadow: 0 0 0 4px rgba(251, 99, 64, 0.4) !important;
    animation: pulse 1s infinite alternate;
  }
  @keyframes pulse {
    0% { transform: scale(1); }
    100% { transform: scale(1.08); }
  }

  .tree-card .node-id {
    font-weight: 700;
    font-size: 13px;
    color: #1e293b;
  }
  .tree-card .node-name {
    font-size: 12px;
    color: #475569;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 2px 0;
  }
  .tree-card .node-meta {
    font-size: 10px;
    color: #64748b;
    border-top: 1px dashed #e2e8f0;
    padding-top: 4px;
    margin-top: 4px;
    display: flex;
    justify-content: space-between;
  }
  
  .tree-children {
    display: flex;
    justify-content: center;
    padding-top: 24px;
    position: relative;
  }
  
  /* Connector Lines using CSS Pseudo Elements */
  .tree-children::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    border-left: 2px solid #cbd5e1;
    width: 0;
    height: 24px;
  }
  .tree-children > .tree-node::before {
    content: '';
    position: absolute;
    top: -24px;
    left: 50%;
    border-top: 2px solid #cbd5e1;
    width: 100%;
    height: 24px;
  }
  .tree-children > .tree-node:first-child::before {
    left: 50%;
    width: 50%;
    border-left: 2px solid #cbd5e1;
    border-radius: 8px 0 0 0;
  }
  .tree-children > .tree-node:last-child::before {
    left: 0;
    width: 50%;
    border-right: 2px solid #cbd5e1;
    border-radius: 0 8px 0 0;
  }
  .tree-children > .tree-node:only-child::before {
    left: 50%;
    width: 0;
    border-top: none;
    border-left: 2px solid #cbd5e1;
  }
  
  .leg-badge {
    position: absolute;
    top: -12px;
    font-size: 9px;
    padding: 1px 6px;
    border-radius: 10px;
    font-weight: 700;
  }
  .leg-badge-A {
    left: 15px;
    background: #5e72e4;
    color: #fff;
  }
  .leg-badge-B {
    right: 15px;
    background: #11cdef;
    color: #fff;
  }

  .vacant-node {
    border: 2px dashed #cbd5e1;
    background: rgba(255,255,255,0.6);
    width: 140px;
    padding: 8px;
    border-radius: 8px;
    color: #94a3b8;
    font-size: 11px;
  }
</style>

<!-- Scripts: Tree Builder, Zoom & Pan, Search -->
<script>
  var rawMembersData = <?= $members_json ?>;
  var rootNodeId = "<?= $root_id ?>";
  var memberMap = {};
  var childrenMap = {};

  // Build quick lookup maps
  var totalCount = 0, activeCount = 0, blockedCount = 0, inactiveCount = 0;

  rawMembersData.forEach(function(m) {
    memberMap[m.id] = m;
    totalCount++;
    var status = (m.status || '').toLowerCase();
    if (status === 'block' || status === 'blocked') {
      blockedCount++;
    } else if (status === 'active') {
      activeCount++;
    } else {
      inactiveCount++;
    }
  });

  document.getElementById('statTotal').innerText = totalCount;
  document.getElementById('statActive').innerText = activeCount;
  document.getElementById('statBlocked').innerText = blockedCount;
  document.getElementById('statInactive').innerText = inactiveCount;

  // Determine top root ID
  if (!memberMap[rootNodeId]) {
    // If root not found in map, find the lowest ID or top position
    var ids = Object.keys(memberMap).map(Number).sort(function(a, b){ return a - b; });
    if (ids.length > 0) {
      rootNodeId = ids[0];
    }
  }

  // Recursive Tree HTML Builder
  function buildTreeHTML(nodeId, legLabel) {
    if (!nodeId || !memberMap[nodeId]) {
      return '';
    }

    var m = memberMap[nodeId];
    var status = (m.status || '').toLowerCase();
    var statusClass = 'inactive-status';
    var statusColor = '#adb5bd';
    var statusText = 'Inactive';

    if (status === 'active') {
      statusClass = 'active-status';
      statusColor = '#2dce89';
      statusText = 'Active';
    } else if (status === 'block' || status === 'blocked') {
      statusClass = 'blocked-status';
      statusColor = '#f5365c';
      statusText = 'Blocked';
    }

    var html = '<div class="tree-node" id="node_' + m.id + '">';
    
    // Leg indicator (Left / Right)
    if (legLabel) {
      html += '<span class="leg-badge leg-badge-' + legLabel + '">' + (legLabel === 'A' ? 'Left' : 'Right') + '</span>';
    }

    // Card Body
    html += '<div class="tree-card ' + statusClass + '" onclick="openMemberModal(\'' + m.id + '\')">';
    html += '  <div class="d-flex justify-content-between align-items-center mb-1">';
    html += '    <span class="node-id"><i class="fas fa-user-circle" style="color:' + statusColor + '"></i> ' + m.id + '</span>';
    html += '    <span class="badge badge-pill badge-' + (status === 'active' ? 'success' : (status === 'block' ? 'danger' : 'secondary')) + '" style="font-size:9px; padding:2px 5px;">' + statusText + '</span>';
    html += '  </div>';
    html += '  <div class="node-name" title="' + (m.name || '') + '">' + (m.name || 'Member') + '</div>';
    html += '  <div class="node-meta">';
    html += '    <span>Rank: ' + (m.rank || 'Member') + '</span>';
    html += '    <span>Pkg: ' + (m.signup_package || '1') + '</span>';
    html += '  </div>';
    html += '</div>';

    // Children (Left / A and Right / B)
    var hasA = m.A && memberMap[m.A];
    var hasB = m.B && memberMap[m.B];

    if (hasA || hasB) {
      html += '<div class="tree-children">';
      if (hasA) {
        html += buildTreeHTML(m.A, 'A');
      } else {
        html += '<div class="tree-node"><span class="leg-badge leg-badge-A">Left</span><div class="vacant-node text-center"><i class="fas fa-user-plus mr-1"></i> Open (A)</div></div>';
      }

      if (hasB) {
        html += buildTreeHTML(m.B, 'B');
      } else {
        html += '<div class="tree-node"><span class="leg-badge leg-badge-B">Right</span><div class="vacant-node text-center"><i class="fas fa-user-plus mr-1"></i> Open (B)</div></div>';
      }
      html += '</div>';
    }

    html += '</div>';
    return html;
  }

  // Render Root Tree
  var treeContainer = document.getElementById('treeContainer');
  if (rootNodeId && memberMap[rootNodeId]) {
    treeContainer.innerHTML = buildTreeHTML(rootNodeId, '');
  } else {
    treeContainer.innerHTML = '<div class="alert alert-info mt-4">कोणतेही मेंबर्स आढळले नाहीत.</div>';
  }

  // -------------------------------------------------------------
  // Pan & Zoom Engine
  // -------------------------------------------------------------
  var viewport = document.getElementById('treeViewport');
  var canvas = document.getElementById('treeCanvas');
  var scale = 0.9;
  var panX = 0;
  var panY = 40;
  var isDragging = false;
  var startX = 0, startY = 0;

  function updateTransform() {
    canvas.style.transform = 'translate(' + panX + 'px, ' + panY + 'px) scale(' + scale + ')';
  }
  updateTransform();

  viewport.addEventListener('mousedown', function(e) {
    if (e.target.closest('.tree-card') || e.target.closest('.modal')) return;
    isDragging = true;
    viewport.style.cursor = 'grabbing';
    startX = e.clientX - panX;
    startY = e.clientY - panY;
  });

  window.addEventListener('mousemove', function(e) {
    if (!isDragging) return;
    panX = e.clientX - startX;
    panY = e.clientY - startY;
    updateTransform();
  });

  window.addEventListener('mouseup', function() {
    isDragging = false;
    viewport.style.cursor = 'grab';
  });

  // Wheel Zoom
  viewport.addEventListener('wheel', function(e) {
    e.preventDefault();
    var zoomFactor = e.deltaY < 0 ? 1.12 : 0.88;
    var newScale = scale * zoomFactor;
    if (newScale >= 0.15 && newScale <= 2.5) {
      scale = newScale;
      updateTransform();
    }
  }, { passive: false });

  // Zoom Buttons
  document.getElementById('btnZoomIn').addEventListener('click', function() {
    if (scale < 2.5) { scale *= 1.2; updateTransform(); }
  });
  document.getElementById('btnZoomOut').addEventListener('click', function() {
    if (scale > 0.2) { scale *= 0.8; updateTransform(); }
  });
  document.getElementById('btnResetZoom').addEventListener('click', function() {
    scale = 0.9;
    panX = 0;
    panY = 40;
    updateTransform();
  });

  // Fullscreen toggle
  document.getElementById('btnFullscreen').addEventListener('click', function() {
    if (!document.fullscreenElement) {
      viewport.requestFullscreen().catch(err => {});
      viewport.style.height = '100vh';
    } else {
      document.exitFullscreen();
      viewport.style.height = '75vh';
    }
  });

  // Collapse / Expand
  var isCollapsed = false;
  document.getElementById('btnCollapseAll').addEventListener('click', function() {
    $('.tree-children').slideUp(200);
    isCollapsed = true;
  });
  document.getElementById('btnExpandAll').addEventListener('click', function() {
    $('.tree-children').slideDown(200);
    isCollapsed = false;
  });

  // -------------------------------------------------------------
  // Search Node & Focus
  // -------------------------------------------------------------
  function performSearch() {
    var query = document.getElementById('treeSearchInput').value.trim().toLowerCase();
    if (!query) return;

    $('.tree-card').removeClass('highlight-search');

    var foundNode = null;
    rawMembersData.forEach(function(m) {
      if (m.id.toString().toLowerCase() === query || (m.name && m.name.toLowerCase().includes(query))) {
        foundNode = m;
      }
    });

    if (foundNode) {
      var nodeElem = document.getElementById('node_' + foundNode.id);
      if (nodeElem) {
        var cardElem = nodeElem.querySelector('.tree-card');
        if (cardElem) cardElem.classList.add('highlight-search');

        // Pan to Node center
        var rect = nodeElem.getBoundingClientRect();
        var vRect = viewport.getBoundingClientRect();
        panX = (vRect.width / 2) - (nodeElem.offsetLeft * scale) - 80;
        panY = (vRect.height / 2) - (nodeElem.offsetTop * scale) - 50;
        scale = 1.0;
        updateTransform();
      }
    } else {
      alert('Member ID किंवा नाव आढळले नाही!');
    }
  }

  document.getElementById('btnTreeSearch').addEventListener('click', performSearch);
  document.getElementById('treeSearchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') performSearch();
  });

  // -------------------------------------------------------------
  // Open Member Modal
  // -------------------------------------------------------------
  window.openMemberModal = function(memberId) {
    var m = memberMap[memberId];
    if (!m) return;

    document.getElementById('modalMemberName').innerText = 'ID: ' + m.id + ' - ' + (m.name || 'Member');
    document.getElementById('modalNameText').innerText = m.name || 'N/A';
    
    var avatar = document.getElementById('modalAvatar');
    avatar.innerText = (m.name ? m.name.charAt(0).toUpperCase() : 'M');
    
    var status = (m.status || '').toLowerCase();
    var statusBadge = document.getElementById('modalStatusBadge');
    if (status === 'active') {
      avatar.style.background = '#2dce89';
      statusBadge.className = 'badge badge-pill badge-success';
      statusBadge.innerText = 'Active';
    } else if (status === 'block' || status === 'blocked') {
      avatar.style.background = '#f5365c';
      statusBadge.className = 'badge badge-pill badge-danger';
      statusBadge.innerText = 'Blocked';
    } else {
      avatar.style.background = '#adb5bd';
      statusBadge.className = 'badge badge-pill badge-secondary';
      statusBadge.innerText = 'Inactive';
    }

    document.getElementById('mId').innerText = m.id;
    document.getElementById('mSponsor').innerText = m.sponsor ? m.sponsor : 'None';
    document.getElementById('mPosition').innerText = m.position ? m.position : 'None';
    document.getElementById('mLeg').innerText = (m.placement_leg === 'A' ? 'Left (A)' : (m.placement_leg === 'B' ? 'Right (B)' : 'Root'));
    document.getElementById('mPackage').innerText = m.prod_name ? m.prod_name : (m.signup_package || 'Standard');
    document.getElementById('mRank').innerText = m.rank || 'Member';
    document.getElementById('mPhone').innerText = m.phone || 'N/A';
    document.getElementById('mJoinDate').innerText = m.join_time || 'N/A';
    document.getElementById('mActDate').innerText = m.activation_date || 'N/A';
    document.getElementById('mChildren').innerText = 'Left: ' + (m.A ? m.A : 'Empty') + ' | Right: ' + (m.B ? m.B : 'Empty');

    document.getElementById('modalEditLink').href = '<?= site_url('users/edit_user/') ?>' + m.id;
    document.getElementById('modalLoginLink').href = '<?= site_url('users/login_member/') ?>' + m.id;

    $('#memberDetailModal').modal('show');
  };
</script>
