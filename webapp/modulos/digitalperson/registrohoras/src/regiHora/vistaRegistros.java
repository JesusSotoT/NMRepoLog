/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package regiHora;
import com.digitalpersona.onetouch.DPFPDataPurpose;
import com.digitalpersona.onetouch.DPFPFeatureSet;
import com.digitalpersona.onetouch.DPFPGlobal;
import com.digitalpersona.onetouch.DPFPSample;
import com.digitalpersona.onetouch.DPFPTemplate;
import com.digitalpersona.onetouch.capture.DPFPCapture;
import com.digitalpersona.onetouch.capture.event.DPFPDataAdapter;
import com.digitalpersona.onetouch.capture.event.DPFPDataEvent;
import com.digitalpersona.onetouch.capture.event.DPFPErrorAdapter;
import com.digitalpersona.onetouch.capture.event.DPFPErrorEvent;
import com.digitalpersona.onetouch.capture.event.DPFPReaderStatusAdapter;
import com.digitalpersona.onetouch.capture.event.DPFPReaderStatusEvent;
import com.digitalpersona.onetouch.capture.event.DPFPSensorAdapter;
import com.digitalpersona.onetouch.capture.event.DPFPSensorEvent;
import com.digitalpersona.onetouch.processing.DPFPEnrollment;
import com.digitalpersona.onetouch.processing.DPFPFeatureExtraction;
import com.digitalpersona.onetouch.processing.DPFPImageQualityException;
import com.digitalpersona.onetouch.verification.DPFPVerification;
import java.awt.Image;
import javax.swing.ImageIcon;
import javax.swing.JOptionPane;
import javax.swing.SwingUtilities;
import javax.swing.UIManager;
import com.digitalpersona.onetouch.verification.DPFPVerificationResult;
import com.mysql.jdbc.CallableStatement;
import java.awt.event.ItemEvent;
import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.text.SimpleDateFormat;
import java.text.*;
import java.util.*;

public class vistaRegistros extends javax.swing.JDialog {
    String nombre;
    String combo;
    java.util.Date utilDate = new java.util.Date();
    java.sql.Date  sqlDate  = new java.sql.Date(utilDate.getTime());
    java.sql.Time  myTime   = new java.sql.Time(utilDate.getTime());
    String Hora;
    Format formatter;
    SimpleDateFormat f = new SimpleDateFormat("EEEE", new Locale("ES")) ;      
    String nombreDia = f.format(sqlDate.getTime());
    String sCadena = nombreDia;
    String Dia = sCadena.substring(0,3);
    String date1, date2, dateNueva;
    Date datew = new Date();
 

    
public vistaRegistros() { 

        DateFormat hora = new SimpleDateFormat("HH:mm:ss");
        hora.setTimeZone(TimeZone.getTimeZone("America/Mexico_City"));
        System.out.println("Fecha en Mexico: " + hora.format(datew));
          formatter = new SimpleDateFormat("HH:mm:ss ");
          Hora = formatter.format(datew);
 
   try {
       
         UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
         } catch (Exception e) {
         JOptionPane.showMessageDialog(null, "Imposible modificar el tema visual", "Lookandfeel inválido.",
         JOptionPane.ERROR_MESSAGE);
         }
        initComponents();
        txtArea.setEditable(false);
        Empleado cliente = new Empleado();
        //cliente.mostrarClientes(comboCliente);
      }   

    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        panHuellas = new javax.swing.JPanel();
        jPanel1 = new javax.swing.JPanel();
        lblImagenHuella = new javax.swing.JLabel();
        panBtns = new javax.swing.JPanel();
        jPanel2 = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        txtArea = new javax.swing.JTextArea();
        btnGuardar = new javax.swing.JButton();
        btnSalir = new javax.swing.JButton();

        setCursor(new java.awt.Cursor(java.awt.Cursor.DEFAULT_CURSOR));
        setIconImages(getIconImages());
        setLocationByPlatform(true);
        setMinimumSize(new java.awt.Dimension(500, 500));
        setResizable(false);
        setSize(new java.awt.Dimension(500, 500));
        setType(java.awt.Window.Type.POPUP);
        addWindowListener(new java.awt.event.WindowAdapter() {
            public void windowClosing(java.awt.event.WindowEvent evt) {
                formWindowClosing(evt);
            }
            public void windowOpened(java.awt.event.WindowEvent evt) {
                formWindowOpened(evt);
            }
        });

        panHuellas.setBorder(javax.swing.BorderFactory.createTitledBorder(null, "Huella Digital Capturada", javax.swing.border.TitledBorder.CENTER, javax.swing.border.TitledBorder.DEFAULT_POSITION));
        panHuellas.setPreferredSize(new java.awt.Dimension(400, 270));

        jPanel1.setBorder(javax.swing.BorderFactory.createBevelBorder(javax.swing.border.BevelBorder.LOWERED));
        jPanel1.setMinimumSize(new java.awt.Dimension(0, 0));

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(lblImagenHuella, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(lblImagenHuella, javax.swing.GroupLayout.DEFAULT_SIZE, 242, Short.MAX_VALUE)
        );

        panBtns.setBorder(javax.swing.BorderFactory.createTitledBorder(null, "Acciones", javax.swing.border.TitledBorder.CENTER, javax.swing.border.TitledBorder.DEFAULT_POSITION));
        panBtns.setPreferredSize(new java.awt.Dimension(400, 190));

        jPanel2.setPreferredSize(new java.awt.Dimension(366, 90));

        txtArea.setColumns(20);
        txtArea.setFont(new java.awt.Font("Lucida Sans", 1, 10)); // NOI18N
        txtArea.setRows(5);
        jScrollPane1.setViewportView(txtArea);

        btnGuardar.setText("Guardar");
        btnGuardar.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnGuardarActionPerformed(evt);
            }
        });

        btnSalir.setText("Salir");
        btnSalir.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnSalirActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addGap(29, 29, 29)
                .addComponent(btnSalir, javax.swing.GroupLayout.PREFERRED_SIZE, 128, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 78, Short.MAX_VALUE)
                .addComponent(btnGuardar, javax.swing.GroupLayout.PREFERRED_SIZE, 128, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap())
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jScrollPane1))
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addComponent(jScrollPane1)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(btnGuardar, javax.swing.GroupLayout.PREFERRED_SIZE, 8, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(btnSalir, javax.swing.GroupLayout.PREFERRED_SIZE, 8, javax.swing.GroupLayout.PREFERRED_SIZE)))
        );

        javax.swing.GroupLayout panBtnsLayout = new javax.swing.GroupLayout(panBtns);
        panBtns.setLayout(panBtnsLayout);
        panBtnsLayout.setHorizontalGroup(
            panBtnsLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, 373, Short.MAX_VALUE)
        );
        panBtnsLayout.setVerticalGroup(
            panBtnsLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, panBtnsLayout.createSequentialGroup()
                .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, 85, Short.MAX_VALUE)
                .addContainerGap())
        );

        javax.swing.GroupLayout panHuellasLayout = new javax.swing.GroupLayout(panHuellas);
        panHuellas.setLayout(panHuellasLayout);
        panHuellasLayout.setHorizontalGroup(
            panHuellasLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
            .addComponent(panBtns, javax.swing.GroupLayout.DEFAULT_SIZE, 385, Short.MAX_VALUE)
        );
        panHuellasLayout.setVerticalGroup(
            panHuellasLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(panHuellasLayout.createSequentialGroup()
                .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(panBtns, javax.swing.GroupLayout.PREFERRED_SIZE, 119, javax.swing.GroupLayout.PREFERRED_SIZE))
        );

        getContentPane().add(panHuellas, java.awt.BorderLayout.CENTER);

        pack();
        setLocationRelativeTo(null);
    }// </editor-fold>//GEN-END:initComponents

    private void formWindowOpened(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowOpened
        Iniciar();
	start();
        EstadoHuellas();
        btnGuardar.setEnabled(true);
        btnGuardar.setVisible(false);
        btnSalir.setVisible(false);
        btnSalir.grabFocus();
    }//GEN-LAST:event_formWindowOpened

    private void formWindowClosing(java.awt.event.WindowEvent evt) {//GEN-FIRST:event_formWindowClosing
         stop();
    }//GEN-LAST:event_formWindowClosing

    private void btnSalirActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnSalirActionPerformed
        System.exit(0);
    }//GEN-LAST:event_btnSalirActionPerformed

    private void btnGuardarActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnGuardarActionPerformed
        guardarHuella();
        Reclutador.clear();
        lblImagenHuella.setIcon(null);      
    }//GEN-LAST:event_btnGuardarActionPerformed

 //Varible que permite iniciar el dispositivo de lector de huella conectado
// con sus distintos metodos.
private DPFPCapture Lector = DPFPGlobal.getCaptureFactory().createCapture();

//Varible que permite establecer las capturas de la huellas, para determina sus caracteristicas
// y poder estimar la creacion de un template de la huella para luego poder guardarla
private DPFPEnrollment Reclutador = DPFPGlobal.getEnrollmentFactory().createEnrollment();

//Esta variable tambien captura una huella del lector y crea sus caracteristcas para auntetificarla
// o verificarla con alguna guardada en la BD
private DPFPVerification Verificador = DPFPGlobal.getVerificationFactory().createVerification();

//Variable que para crear el template de la huella luego de que se hallan creado las caracteriticas
// necesarias de la huella si no ha ocurrido ningun problema
private DPFPTemplate template;
public static String TEMPLATE_PROPERTY = "template";

  protected void Iniciar(){
   Lector.addDataListener(new DPFPDataAdapter() {
    @Override public void dataAcquired(final DPFPDataEvent e) {
    SwingUtilities.invokeLater(new Runnable() {	public void run() {
    EnviarTexto("La Huella Digital ha sido Capturada");
    ProcesarCaptura(e.getSample());
    }});}
   });

   Lector.addReaderStatusListener(new DPFPReaderStatusAdapter() {
    @Override public void readerConnected(final DPFPReaderStatusEvent e) {
    SwingUtilities.invokeLater(new Runnable() {	public void run() {
    EnviarTexto("El Sensor de Huella Digital esta Activado o Conectado");
    }});}
    @Override public void readerDisconnected(final DPFPReaderStatusEvent e) {
    SwingUtilities.invokeLater(new Runnable() {	public void run() {
    EnviarTexto("El Sensor de Huella Digital esta Desactivado o no Conectado");
    }});}
   });

   Lector.addSensorListener(new DPFPSensorAdapter() {
    @Override public void fingerTouched(final DPFPSensorEvent e) {
    SwingUtilities.invokeLater(new Runnable() {	public void run() {
    EnviarTexto("El dedo ha sido colocado sobre el Lector de Huella");
    }});}
    @Override public void fingerGone(final DPFPSensorEvent e) {
    SwingUtilities.invokeLater(new Runnable() {	public void run() {
    EnviarTexto("El dedo ha sido quitado del Lector de Huella");
    }});}
   });

   Lector.addErrorListener(new DPFPErrorAdapter(){
    public void errorReader(final DPFPErrorEvent e){
    SwingUtilities.invokeLater(new Runnable() {  public void run() {
    EnviarTexto("Error: "+e.getError());
    }});}
   });
}
  
 public DPFPFeatureSet featuresinscripcion;
 public DPFPFeatureSet featuresverificacion;
   
 public  void ProcesarCaptura(DPFPSample sample)
 {
     
 // Procesar la muestra de la huella y crear un conjunto de características con el propósito de inscripción.
 featuresinscripcion = extraerCaracteristicas(sample, DPFPDataPurpose.DATA_PURPOSE_ENROLLMENT);

 // Procesar la muestra de la huella y crear un conjunto de características con el propósito de verificacion.
 featuresverificacion = extraerCaracteristicas(sample, DPFPDataPurpose.DATA_PURPOSE_VERIFICATION);
btnGuardar.setEnabled(true);

 // Comprobar la calidad de la muestra de la huella y lo añade a su reclutador si es bueno
 if (featuresinscripcion != null)
    
     try{
     System.out.println("Las Caracteristicas de la Huella han sido creada");
     Reclutador.addFeatures(featuresinscripcion);// Agregar las caracteristicas de la huella a la plantilla a crear

     // Dibuja la huella dactilar capturada.
     Image image=CrearImagenHuella(sample);
     DibujarHuella(image);

      try {
                identificarHuella();
                guardarHuella();
               // Reclutador.clear();
                //lblImagenHuella.setIcon(null);
    
            Reclutador.clear();
        } catch (IOException ex) {
            Logger.getLogger(vistaRegistros.class.getName()).log(Level.SEVERE, null, ex);
        }

     }catch (DPFPImageQualityException ex) {
     System.err.println("Error: "+ex.getMessage());
     }

     finally {
     EstadoHuellas();
     // Comprueba si la plantilla se ha creado.
	switch(Reclutador.getTemplateStatus())
        {
            case TEMPLATE_STATUS_READY:	// informe de éxito y detiene  la captura de huellas
	    stop();
            setTemplate(Reclutador.getTemplate());
	    EnviarTexto("La Plantilla de la Huella ha Sido Creada, ya puede Verificarla o Identificarla");
            guardarHuella();
     
            btnGuardar.setEnabled(true);
            btnGuardar.grabFocus();
            break;

	    case TEMPLATE_STATUS_FAILED: // informe de fallas y reiniciar la captura de huellas
	    Reclutador.clear();
            stop();
	    EstadoHuellas();
	    setTemplate(null);
	    JOptionPane.showMessageDialog(vistaRegistros.this, "La Plantilla de la Huella no pudo ser creada, Repita el Proceso", "Inscripcion de Huellas Dactilares", JOptionPane.ERROR_MESSAGE);
	    start();
	    break;
	}
	     }
}
 
 
  public  DPFPFeatureSet extraerCaracteristicas(DPFPSample sample, DPFPDataPurpose purpose){
     DPFPFeatureExtraction extractor = DPFPGlobal.getFeatureExtractionFactory().createFeatureExtraction();
     try {
      return extractor.createFeatureSet(sample, purpose);
     } catch (DPFPImageQualityException e) {
      return null;
     }
}

  public  Image CrearImagenHuella(DPFPSample sample) {
	return DPFPGlobal.getSampleConversionFactory().createImage(sample);
}

  public void DibujarHuella(Image image) {
        lblImagenHuella.setIcon(new ImageIcon(
        image.getScaledInstance(lblImagenHuella.getWidth(), lblImagenHuella.getHeight(), Image.SCALE_DEFAULT)));
        repaint();
 }

  public  void EstadoHuellas(){
	EnviarTexto("Muestra de Huellas Necesarias para Guardar Template "+ Reclutador.getFeaturesNeeded());
}

public void EnviarTexto(String string) {
        txtArea.append(string + "\n");
}

public  void start(){
	Lector.startCapture();
	EnviarTexto("Utilizando el Lector de Huella Dactilar ");
}

public  void stop(){
        Lector.stopCapture();
        EnviarTexto("No se está usando el Lector de Huella Dactilar ");
}

public DPFPTemplate getTemplate() {
        return template;
    }

 public void setTemplate(DPFPTemplate template) {
        DPFPTemplate old = this.template;
	this.template = template;
	firePropertyChange(TEMPLATE_PROPERTY, old, template);
    }

Conexion con=new Conexion();
  /*
  * Guarda los datos de la huella digital actual en la base de datos
  */

public void guardarHuella(){
       
     formatter = new SimpleDateFormat("HH:mm:ss ");
     Hora = formatter.format(new Date());
         
       //Obtiene los datos del template de la huella actual
     ByteArrayInputStream datosHuella = new ByteArrayInputStream(template.serialize());
     Integer tamañoHuella=template.serialize().length;
       
    try { 
            DateFormat dateFormat = new SimpleDateFormat("HH:mm:ss");
            Date date1, date2, dateNueva;
            String Mensaje =""; 
            String hora1 = "14:00:00";
            String hora2 = "18:00:00";
            String horaNueva = Hora;
            date1 = dateFormat.parse(hora1);
            date2 = dateFormat.parse(hora2);
            dateNueva = dateFormat.parse(horaNueva);

//if ((date1.compareTo(dateNueva) <= 0) && (date2.compareTo(dateNueva) >= 0)){
//	System.out.println("La horax " + horaNueva + " está entre " + hora1 + " y " + hora2);
//try{
  /*   if(horaNueva!=null){
        salidaC=Hora;
          System.out.println("entro al horanueva");      
      }
  */  
    
    
         
     //Establece los valores para la sentencia SQL
     Connection c=con.conectar(); //establece la conexion con la BD
     CallableStatement insertarRegistroHuellaStmt ;
     insertarRegistroHuellaStmt =
     (CallableStatement) c.prepareCall("{CALL usp_InsertarRegistroHuella(?,?,?,?,?)}");
     
       insertarRegistroHuellaStmt.setInt(1,Integer.parseInt(nombre));
       insertarRegistroHuellaStmt.setDate(2,sqlDate);
       insertarRegistroHuellaStmt.setString(3,Dia);
       insertarRegistroHuellaStmt.setString(4,Hora); 
       insertarRegistroHuellaStmt.registerOutParameter(5, java.sql.Types.VARCHAR);  
       insertarRegistroHuellaStmt.executeUpdate();
       Mensaje = insertarRegistroHuellaStmt.getString(5); 
       Reclutador.clear();
       lblImagenHuella.setIcon(null);  
   //Ejecuta la sentencia
    if (!Mensaje.isEmpty()){
       JOptionPane.showMessageDialog(null,"-" + Mensaje + "-");
             }  
            JOptionPane.showMessageDialog(null,"MARCO A LAS:" + Hora + "-");
            insertarRegistroHuellaStmt.close();
            JOptionPane.showMessageDialog(null,"Huella Guardada Correctamente");
            con.desconectar();
    
   //  } catch (SQLException ex) {
     //Si ocurre un error lo indica en la consola
    // System.err.println("Error al guardar los datos de la huella.");
      //  System.err.println(ex);
  //   }
//} else 
//{
//      JOptionPane.showMessageDialog(null, "HORARIO: ","Verificacion de Huella", JOptionPane.INFORMATION_MESSAGE);
////      //  Connection c=con.conectar(); //establece la conexion con la BD
////   //  PreparedStatement guardarStmt = c.prepareStatement("INSERT INTO nomi_empleadoHuella(idEmpleado,horaentrada,fecha,dia,horaComida) values(?,?,?,?,?)");
// System.out.println("La horaxy " + horaNueva + " no está entre " + hora1 + " y " + hora2);
////}
} catch (ParseException parseException){ parseException.printStackTrace();
}   
 catch (SQLException parseException){ parseException.printStackTrace();
}   
   finally{
     con.desconectar();
     }
   }
   
 /**
  * Identifica a una persona registrada por medio de su huella digital
  */
public void identificarHuella() throws IOException{ 
   
  try { 
     //Establece los valores para la sentencia SQL
     Connection c=con.conectar(); 
       //Obtiene todas las huellas de la bd 
      // PreparedStatement identificarStmt = c.prepareStatement("SELECT idEmpleado,huella FROM nomi_empleadoHuella"); 
      PreparedStatement identificarStmt = c.prepareStatement("SELECT nr.idEmpleado,nr.huella,nh.nombreEmpleado,nh.apellidoPaterno,nh.apellidoMaterno FROM nomi_empleadoReHuella as nr inner join nomi_empleados as nh\n" +
"on nr.idEmpleado = nh.idEmpleado order by nh.nombreEmpleado asc;");  
      ResultSet rs = identificarStmt.executeQuery();

       //Si se encuentra el nombre en la base de datos
       while(rs.next()){
       //Lee la plantilla de la base de datos
       byte templateBuffer[] = rs.getBytes("huella");
       nombre=rs.getString("idEmpleado");
       String nombreE=rs.getString("nombreEmpleado");
       String ap=rs.getString("apellidoPaterno");
       String am=rs.getString("apellidoMaterno");
       
       //Byte datosHuella = new bytetemplate.serialize();
      //DPFPTemplate datosHuella2 = DPFPGlobal.getTemplateFactory().createTemplate(datosHuella);
     //Crea una nueva plantilla a partir de la guardada en la base de datos
       DPFPTemplate referenceTemplate = DPFPGlobal.getTemplateFactory().createTemplate(templateBuffer);
       //Envia la plantilla creada al objeto contendor de Template del componente de huella digital
       setTemplate(referenceTemplate);

       // Compara las caracteriticas de la huella recientemente capturda con la
       // alguna plantilla guardada en la base de datos que coincide con ese tipo
       DPFPVerificationResult result = Verificador.verify(featuresverificacion, getTemplate());

       //compara las plantilas (actual vs bd)
       //Si encuentra correspondencia dibuja el mapa
       //e indica el nombre de la persona que coincidió.
       if (result.isVerified()==true){ //Si hizo match..
       //crea la imagen de los datos guardado de las huellas guardadas en la base de datos
    JOptionPane.showMessageDialog(null, "Las huella captura pertenece a: "+nombreE+" "+ap+ " "+am,"Verificacion de Huella", JOptionPane.INFORMATION_MESSAGE);
         
    System.out.println("Las huella captura pertenece a: "+nombreE+" "+ap+ " "+am );
    
   btnGuardar.setEnabled(false);
          return;
               
     }
       }
       //Si no encuentra alguna huella correspondiente al nombre lo indica con un mensaje
       JOptionPane.showMessageDialog(null, "No existe ningún registro que coincida con la huella", "Verificacion de Huella", JOptionPane.ERROR_MESSAGE);
         Iniciar();
      
          // int input = JOptionPane.showOptionDialog(null, "No existe ningún registro que coincida con la huella", "The title", JOptionPane.OK_CANCEL_OPTION, JOptionPane.INFORMATION_MESSAGE, null, null, null);  
               
     
       } catch (SQLException e) {
       //Si ocurre un error lo indica en la consola
          System.err.println("Error al identificar huella dactilar."+e.getMessage());
    }
     finally{
  con.desconectar();
     }
   }

  public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new vistaRegistros().setVisible(true);
            }
        });
   
      }  
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton btnGuardar;
    private javax.swing.JButton btnSalir;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JLabel lblImagenHuella;
    private javax.swing.JPanel panBtns;
    private javax.swing.JPanel panHuellas;
    private javax.swing.JTextArea txtArea;
    // End of variables declaration//GEN-END:variables
}
