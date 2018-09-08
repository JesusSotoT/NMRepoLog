package jcombobox;

import java.sql.ResultSet;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JComboBox;
import javax.swing.JOptionPane;

/**
 *
 * @author CORE I5
 */
public class Empleado {

    private String idEmpleado;
    private String nombreEmpleado;
    private String codigo;
    private String apellidoPaterno;
    private String apellidoMaterno;
 
    public Empleado(String idEmpleado, String nombreEmpleado,String codigo,String apellidoPaterno,String apellidoMaterno) {
      
        this.idEmpleado = idEmpleado; 
        this.nombreEmpleado = nombreEmpleado;
        this.codigo = codigo;
        this.apellidoPaterno = apellidoPaterno;
        this.apellidoMaterno = apellidoMaterno;
   }
    
    public Empleado(){
        
    }

    public String getIdEmpleado() {
        return idEmpleado;
    }

    public void setIdEmpleado(String apellido) {
        this.idEmpleado = apellido;
    }
    public String getNombre() {
        return nombreEmpleado;
    }

    public void setNombre(String nombreEmpleado) {
        this.nombreEmpleado = nombreEmpleado;
    }


    public String getcodigo() {
        return codigo;
    }

    public void setcodigo(String codigo) {
        this.codigo = codigo;
    }
       
    public void mostrarClientes(JComboBox<Empleado> comboCliente){
        try {
            Conexion conn = new Conexion();
            conn.ConexionMySQL();
           // ResultSet rs = conn.consultar("SELECT idEmpleado,codigo,apellidoPaterno,apellidoMaterno,nombreEmpleado FROM nomi_empleados ORDER BY nombreEmpleado ASC");
           
      ResultSet rs = conn.consultar("SELECT ne.idEmpleado,ne.codigo,ne.apellidoPaterno,ne.apellidoMaterno,ne.nombreEmpleado\n" +
"FROM nomi_empleados as ne left join nomi_empleadoReHuella as er\n" +
"on ne.idEmpleado = er.idEmpleado  where er.huella is null;");
    
            while(rs.next()){
                
                comboCliente.addItem(
                        new Empleado(
                                rs.getString("idEmpleado"), 
                                rs.getNString("nombreEmpleado"),
                                rs.getNString("codigo"),
                                rs.getNString("apellidoPaterno"),
                                rs.getNString("apellidoMaterno")
                              
                                
                              
                                
                               
                        )
                        
                );
            }
        } catch (Exception ex) {
            Logger.getLogger(Empleado.class.getName()).log(Level.SEVERE, null, ex);
            JOptionPane.showMessageDialog(null, "ERROR AL MOSTRAR LOS CLIENTES");
        }
    //   System.out.println(nombreEmpleado);
    }
    
    @Override
    public String toString(){
        return codigo+"-"+" "+nombreEmpleado+" "+apellidoPaterno+" "+apellidoMaterno;
    }
    
}
