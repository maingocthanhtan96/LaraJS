@extends('layouts/contentLayoutMaster')

@section('title', '2 Columns')

@section('vendor-style')
        <!-- vendor css files -->
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/ui/prism.min.css')) }}">
@endsection
@section('content')
<!-- Description -->
<section id="description" class="card">
  <div class="card-header">
    <h4 class="card-title">Description</h4>
  </div>
  <div class="card-content">
    <div class="card-body">
      <div class="card-text">
        <p>2 Columns layout is the most common and popular layout, it has a navigation with content section. This layout
          use the common navbar and footer sections, however you can add customized header or footer on page level.</p>
        <div class="alert alert-warning" role="alert">
          Vuexy Admin Template default layout is 2 columns. If you do not define pageConfig block on page or template
          level, it will consider 2 columns by default.
        </div>
      </div>
    </div>
  </div>
</section>
<!--/ Description -->
<!-- CSS Classes -->
<section id="css-classes" class="card">
  <div class="card-header">
    <h4 class="card-title">CSS Classes</h4>
  </div>
  <div class="card-content">
    <div class="card-body">
      <div class="card-text">
        <p>This table contains all classes related to the 2 columns layout. This is a custom layout classes for 2
          columns layout page requirements.</p>
        <p>All these options can be set via following classes:</p>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Classes</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row"><code>.2-columns</code></th>
                <td>You can create 2 columns layout by adding <code>2-columns</code> class in <code>&lt;body&gt;</code>
                  tag.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<!--/ CSS Classes -->
<!-- HTML Markup -->
<section id="html-markup" class="card">
  <div class="card-header">
    <h4 class="card-title">HTML Markup</h4>
  </div>
  <div class="card-content">
    <div class="card-body">
      <div class="card-text">
        <p>This layout has a navigation and content sections with common header & footer.</p>
        <p>Vuexy has a ready to use starter kit, you can use this layout directly by using the starter kit pages from
          the <code>vuexy-html-admin-dashboard-template/starter-kit</code> folder.</p>
        <pre class="language-html">
        <code class="language-html">
            &lt;!DOCTYPE html&gt;
              &lt;html lang="en"&gt;
                &lt;head&gt;&lt;/head&gt;
                &lt;body data-menu="vertical-menu-modern" class="vertical-layout vertical-menu-modern 2-columns navbar-floating footer-static menu-expanded"&gt;

                  &lt;!-- fixed-top--&gt;
                  &lt;nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow"&gt;
                  &lt;/nav&gt;

                  &lt;!-- BEGIN Navigation--&gt;
                  &lt;div class="main-menu menu-fixed menu-light menu-accordion menu-shadow"&gt;
                  &lt;/div&gt;
                  &lt;!-- END Navigation--&gt;

                  &lt;!-- BEGIN Content--&gt;
                  &lt;div class="app-content content"&gt;
                      &lt;div class="content-wrapper"&gt;
                      &lt;/div&gt;
                  &lt;/div&gt;
                  &lt;!-- END Content--&gt;

                  &lt;!-- START FOOTER LIGHT--&gt;
                  &lt;footer class="footer footer-static footer-light"&gt;
                  &lt;/footer&gt;
                  &lt;!-- END FOOTER LIGHT--&gt;

                &lt;/body&gt;
              &lt;/html&gt;
        </code>
        </pre>
      </div>
    </div>
  </div>
</section>
<!--/ HTML Markup -->
@endsection
@section('vendor-script')
        <!-- vendor files -->
        <script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
@endsection
