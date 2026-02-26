<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Complete Blood Count Report</title>
    <link rel="stylesheet" href="hematologiv2.css" />
  </head>

  <body>
    <div class="report">
      <div class="row">
        <div class="row">
          <pre class="header-block">
DATE: 10/04/2025
LOT #: M445IN
ANALYSIS
SEQ #: 5
STARTUP PASSED
T : 21.0 Deg C
</pre
          >

          <pre class="header-block">
TIME: 18:47
</pre
          >
        </div>

        <div>TIME: 18:47</div>
      </div>

      <div class="separator"></div>

      <div class="row">
        <div class="col">
          <pre>
WBC :   7.0   10³/mm³   (5.0 - 11.0)
RBC :   4.26  10⁶/mm³   (3.80 - 6.50)
HGB :  13.0   g/dl      (11.0 - 16.0)
HCT :  36.7   %         (36.0 - 54.0)
PLT :  251    10³/mm³   (150 - 450)
PCT :  0.191  %         (0.100 - 0.500)
</pre
          >
        </div>

        <div class="col">
          <pre>
MCV : 86  µm³   (76 - 96)
MCH : 30.4 pg   (27 - 32)
MCHC: 35.3 g/dl (30 - 35)
RDW : 13.4 %    (11.5 - 14.5)
MPV : 7.8 µm³   (6.5 - 9.5)
PDW : 11.8 %    (10 - 18)
</pre
          >
        </div>
      </div>

      <div class="separator"></div>

      <div class="row">
        <div class="col">
          <pre>
DIFF

%LYM : 30.9 %  (20.0 - 40.0)
%MON :  7.8 %  (2.0 - 8.0)
%GRA : 61.3 %  (43.0 - 76.0)
</pre
          >
        </div>

        <div class="col">
          <pre>
            

#LYM : 2.1 10³/mm³ (1.2 - 3.2)
#MON : 0.5 10³/mm³ (0.3 - 0.8)
#GRA : 4.4 10³/mm³ (1.2 - 6.8)
</pre
          >
        </div>
      </div>

      <div class="graph-row">
        <div>
          <div class="title">WBC</div>
          <canvas id="wbc"></canvas>
        </div>

        <div>
          <div class="title">RBC</div>
          <canvas id="rbc"></canvas>
        </div>

        <div>
          <div class="title">PLT</div>
          <canvas id="plt"></canvas>
        </div>
      </div>
    </div>
  </body>
  <script src="historgramv2.js"></script>
</html>
