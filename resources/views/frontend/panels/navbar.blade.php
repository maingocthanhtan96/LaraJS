@if($configData["mainLayoutType"] == 'horizontal' && isset($configData["mainLayoutType"]))
<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu {{ $configData['navbarColor'] }} navbar-fixed">
  <div class="navbar-header d-xl-block d-none">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item"><a class="navbar-brand" href="dashboard-analytics">
          <div class="brand-logo"></div>
        </a></li>
    </ul>
  </div>
  @else
  <nav
    class="header-navbar navbar-expand-lg navbar navbar-with-menu {{ $configData['navbarClass'] }} navbar-light navbar-shadow {{ $configData['navbarColor'] }}">
    @endif
    <div class="navbar-wrapper">
      <div class="navbar-container content">
        <div class="navbar-collapse" id="navbar-mobile">
          <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav">
              <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                  href="#"><i class="ficon feather icon-menu"></i></a></li>
            </ul>
            <ul class="nav navbar-nav bookmark-icons">
              <li class="nav-item d-none d-lg-block"><a class="nav-link" href="sk-layout-2-columns"
                  data-toggle="tooltip" data-placement="top" title="2-Columns"><i
                    class="ficon feather icon-sidebar"></i></a></li>
            </ul>
            <ul class="nav navbar-nav">
              <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i
                    class="ficon feather icon-star warning"></i></a>
                <div class="bookmark-input search-input">
                  <div class="bookmark-input-icon"><i class="feather icon-search primary"></i></div>
                  <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="0"
                    data-search="laravel-starter-list" />
                  <ul class="search-list search-list-bookmark"></ul>
                </div>
                <!-- select.bookmark-select-->
                <!--   option 1-Column-->
                <!--   option 2-Column-->
                <!--   option Static Layout-->
              </li>
            </ul>
          </div>
          <ul class="nav navbar-nav float-right">
            <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag"
                href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                  class="flag-icon flag-icon-us"></i><span class="selected-language">English</span></a>
              <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="#"
                  data-language="en"><i class="flag-icon flag-icon-us"></i> English</a><a class="dropdown-item" href="#"
                  data-language="fr"><i class="flag-icon flag-icon-fr"></i> French</a><a class="dropdown-item" href="#"
                  data-language="de"><i class="flag-icon flag-icon-de"></i> German</a><a class="dropdown-item" href="#"
                  data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese</a></div>
            </li>
            <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i
                  class="ficon feather icon-maximize"></i></a></li>
            <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i
                  class="ficon feather icon-search"></i></a>
              <div class="search-input">
                <div class="search-input-icon"><i class="feather icon-search primary"></i></div>
                <input class="input" type="text" placeholder="Explore Vuexy..." tabindex="-1"
                  data-search="starter-list" />
                <div class="search-input-close"><i class="feather icon-x"></i></div>
                <ul class="search-list search-list-main"></ul>
              </div>
            </li>
            <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#"
                data-toggle="dropdown"><i class="ficon feather icon-bell"></i><span
                  class="badge badge-pill badge-primary badge-up">5</span></a>
              <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                <li class="dropdown-menu-header">
                  <div class="dropdown-header m-0 p-2">
                    <h3 class="white">5 New</h3><span class="grey darken-2">App Notifications</span>
                  </div>
                </li>
                <li class="scrollable-container media-list">
                  <!-- a(href='javascript:void(0)').d-flex.justify-content-between-->
                  <!--   .d-flex.align-items-start-->
                  <!--       i.feather.icon-plus-square-->
                  <!--       .mx-1-->
                  <!--         .font-medium.block.notification-title New Message-->
                  <!--         small Are your going to meet me tonight?-->
                  <!--   small 62 Days ago--><a class="d-flex justify-content-between" href="javascript:void(0)">
                    <div class="media d-flex align-items-start">
                      <div class="media-left"><i class="feather icon-plus-square font-medium-5 primary"></i></div>
                      <div class="media-body">
                        <h6 class="primary media-heading">You have new order!</h6><small class="notification-text"> Are
                          your going to meet me
                          tonight?</small>
                      </div><small>
                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">9 hours
                          ago</time></small>
                    </div>
                  </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                    <div class="media d-flex align-items-start">
                      <div class="media-left"><i class="feather icon-download-cloud font-medium-5 success"></i></div>
                      <div class="media-body">
                        <h6 class="success media-heading red darken-1">99% Server load</h6>
                        <small class="notification-text">You got new order of goods.</small>
                      </div><small>
                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">5 hour
                          ago</time></small>
                    </div>
                  </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                    <div class="media d-flex align-items-start">
                      <div class="media-left"><i class="feather icon-alert-triangle font-medium-5 danger"></i></div>
                      <div class="media-body">
                        <h6 class="danger media-heading yellow darken-3">Warning notifixation
                        </h6><small class="notification-text">Server have 99% CPU usage.</small>
                      </div><small>
                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                    </div>
                  </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                    <div class="media d-flex align-items-start">
                      <div class="media-left"><i class="feather icon-check-circle font-medium-5 info"></i></div>
                      <div class="media-body">
                        <h6 class="info media-heading">Complete the task</h6><small class="notification-text">Cake
                          sesame snaps cupcake</small>
                      </div><small>
                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last
                          week</time></small>
                    </div>
                  </a><a class="d-flex justify-content-between" href="javascript:void(0)">
                    <div class="media d-flex align-items-start">
                      <div class="media-left"><i class="feather icon-file font-medium-5 warning"></i></div>
                      <div class="media-body">
                        <h6 class="warning media-heading">Generate monthly report</h6><small
                          class="notification-text">Chocolate cake oat cake tiramisu
                          marzipan</small>
                      </div><small>
                        <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">Last
                          month</time></small>
                    </div>
                  </a>
                </li>
                <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center" href="javascript:void(0)">Read
                    all notifications</a></li>
              </ul>
            </li>
            <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#"
                data-toggle="dropdown">
                <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">John
                    Doe</span><span class="user-status">Available</span></div><span><img class="round"
                    src="{{asset('images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="40"
                    width="40" /></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="javascript:void(0)"><i
                    class="feather icon-user"></i> Edit Profile</a><a class="dropdown-item" href="javascript:void(0)"><i
                    class="feather icon-mail"></i> My
                  Inbox</a><a class="dropdown-item" href="javascript:void(0)"><i class="feather icon-check-square"></i>
                  Task</a><a class="dropdown-item" href="javascript:void(0)"><i class="feather icon-message-square"></i>
                  Chats</a>
                <div class="dropdown-divider"></div><a class="dropdown-item" href="javascript:void(0)"><i
                    class="feather icon-power"></i> Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  {{-- Search Start Here --}}
  <ul class="main-search-list-defaultlist d-none">
    <li class="d-flex align-items-center">
      <a class="pb-25" href="#">
        <h6 class="text-primary mb-0">Files</h6>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between w-100" href="#">
        <div class="d-flex">
          <div class="ml-0 mr-50"><img src="{{ asset('images/icons/xls.png') }}" alt="png" height="32" />
          </div>
          <div class="search-data">
            <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing
              Manager</small>
          </div>
        </div><small class="search-data-size mr-50 text-muted">&apos;17kb</small>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between w-100" href="#">
        <div class="d-flex">
          <div class="ml-0 mr-50"><img src="{{ asset('images/icons/jpg.png') }}" alt="png" height="32" />
          </div>
          <div class="search-data">
            <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd
              Developer</small>
          </div>
        </div><small class="search-data-size mr-50 text-muted">&apos;11kb</small>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between w-100" href="#">
        <div class="d-flex">
          <div class="ml-0 mr-50"><img src="{{ asset('images/icons/pdf.png') }}" alt="png" height="32" />
          </div>
          <div class="search-data">
            <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital
              Marketing Manager</small>
          </div>
        </div><small class="search-data-size mr-50 text-muted">&apos;150kb</small>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between w-100" href="#">
        <div class="d-flex">
          <div class="ml-0 mr-50"><img src="{{ asset('images/icons/doc.png') }}" alt="png" height="32" />
          </div>
          <div class="search-data">
            <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web
              Designer</small>
          </div>
        </div><small class="search-data-size mr-50 text-muted">&apos;256kb</small>
      </a>
    </li>
    <li class="d-flex align-items-center">
      <a class="pb-25" href="#">
        <h6 class="text-primary mb-0">Members</h6>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
        <div class="d-flex align-items-center">
          <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-8.jpg') }}" alt="png"
              height="32" /></div>
          <div class="search-data">
            <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
          </div>
        </div>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
        <div class="d-flex align-items-center">
          <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-1.jpg') }}" alt="png"
              height="32" /></div>
          <div class="search-data">
            <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd
              Developer</small>
          </div>
        </div>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
        <div class="d-flex align-items-center">
          <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-14.jpg') }}" alt="png"
              height="32" /></div>
          <div class="search-data">
            <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing
              Manager</small>
          </div>
        </div>
      </a>
    </li>
    <li class="auto-suggestion d-flex align-items-center cursor-pointer">
      <a class="d-flex align-items-center justify-content-between py-50 w-100" href="#">
        <div class="d-flex align-items-center">
          <div class="avatar mr-50"><img src="{{ asset('images/portrait/small/avatar-s-6.jpg') }}" alt="png"
              height="32" /></div>
          <div class="search-data">
            <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
          </div>
        </div>
      </a>
    </li>
  </ul>
  <ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer">
      <a class="d-flex align-items-center justify-content-between w-100 py-50">
        <div class="d-flex justify-content-start"><span class="mr-75 feather icon-alert-circle"></span><span>No
            results found.</span></div>
      </a>
    </li>
  </ul>
  {{-- Search Ends --}}
  <!-- END: Header-->